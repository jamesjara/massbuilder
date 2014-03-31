<?php

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_Query');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

global $gData, $gBlogId;

$client = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service, null,
        Zend_Gdata_ClientLogin::DEFAULT_SOURCE, null, null, 
        Zend_Gdata_ClientLogin::CLIENTLOGIN_URI, 'GOOGLE');

		
function decodeurl( $gdClient , $url ){
	$feed = $gdClient->get( $url );
	return json_decode( $feed->getBody() , true );
}


function getBlogId($gData, $feed)
{
    $query = new Zend_Gdata_Query($feed);
    $feed = $gData->getFeed($query);
    preg_match('/blog-([0-9]+)/', $feed->id->text, $match);
    if (isset($match[1]))
    {
        return $match[1];
    }
    return false;
}

$gdClient = new Zend_Gdata($client);

function getBlogs(  ) {
	global $gdClient ;
	$data = decodeurl( $gdClient , 'http://www.blogger.com/feeds/default/blogs?alt=json' );
	$blogs = $data['feed']["entry"];
	$data_fixed = null;
	$x	= 0;
	foreach( $blogs as $blog ){
		$data_fixed[$x]['title'] 		= $blog["id"]['$t'];
		$data_fixed[$x]['published']	= $blog["published"]['$t'];
		$data_fixed[$x]['published']	= $blog["updated"]['$t'];
		$data_fixed[$x]['summary']		= $blog["summary"]['$t'];	
		$data_fixed[$x]['url']			= $blog["link"][1]['href'];		
		$data_fixed[$x]['category']		= null;
		if( $blog['category'] ) foreach( $blog['category'] as $categorias ){
			$data_fixed[$x]['category'] .= $categorias["term"].',';
		}
		$data_fixed[$x]['autor']		= $blog['author'][0]['name']['$t'];
		$data_fixed[$x]['autor_uri']	= $blog['author'][0]['uri']['$t'];
		$data_fixed[$x]['autor_email']	= $blog['author'][0]['email']['$t'];
		$data_fixed[$x]['autor_email']	= $blog['author'][0]['email']['$t'];
		
		$data_fixed[$x]['IS_PUBLIC_BLOG']		= $blog['gd$extendedProperty'][0]['value'];
		$data_fixed[$x]['PICASAWEB_ALBUM_ID']	= $blog['gd$extendedProperty'][1]['value'];
		$data_fixed[$x]['NUM_POSTS']			= $blog['gd$extendedProperty'][2]['value'];
		$data_fixed[$x]['HAS_ADMIN_ACCESS']		= $blog['gd$extendedProperty'][3]['value'];
		$x++;
	}
	return $data_fixed ;
}

function getBlogEntries( $blogID ) {
	global $gdClient ;
	$blog_posts = array();
	$query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $blogID . '/posts/default?max-results=500');
	$feed = $gdClient->getFeed($query);
	$nr_posts = $feed->totalResults->text;
	// parse each entry
	foreach($feed->entries as $entry) {
		
		$idText = explode('.post-', $entry->id);
		$postID = $idText[1];
		// labels
		$post_labels = array();
		foreach( $entry->category as $category ) {
			$post_labels[] = $category->term;
		}
		// thumbnail
		if( $entry->extensionElements[0]->rootElement == 'thumbnail' ) {
			$thumbnail = $entry->extensionElements[0]->extensionAttributes['url']['value'];
		} else {
			$thumbnail = '';
		}
		
		// create post array
		$post = array(
					'id' => $postID,
					'titulo' => $entry->title->text,
					'contenido' => $entry->content->text,
					'categorias' => implode(",", $post_labels),
					'thumbnail' => $thumbnail,
					'fuente' => 'blogger',
					'published' => $entry->published->text , 
					'updated' => $entry->updated->text ,
					'islive' => true,
					'author' => $entry->author[0]->name->text		
				);
		$blog_posts[$postID] = $post;
	}
	return $blog_posts;
}

function getBlogExport( $blogID ) {
	global $gdClient ;
	global $username ;
	global $password ;
	$blog_posts = array();
    $body = decodeurl( $gdClient , 'http://www.blogger.com/feeds/' . $blogID . '/archive' );
	return $body;
}


function createPublishedPost($gBlogId, $myBlogTitle, $myBlogText , $gCategory ,$isdraft = false ) {
	global $gdClient ;
	sleep( rand(1,10) );
    $uri = 'https://www.blogger.com/feeds/' . $gBlogId . '/posts/default';
    $entry = $gdClient->newEntry();
	//var_dump($gCategory);
	$tags = explode(",",$gCategory);
	if(is_array($tags)){
		$labels = array();
		foreach($tags as $tag){
			$labels[] = $gdClient->newCategory(trim($tag), 'http://www.blogger.com/atom/ns#');
		}	
		/* Adding tags to post */
		$entry->setCategory($labels);
    }
	
    $entry->title = $gdClient->newTitle($myBlogTitle);
    $content = $gdClient->newContent($myBlogText);
    $content->setType('text');
    $entry->content = $content;
	
	if($isdraft==true){
		$control = $gdClient->newControl();
		$draft = $gdClient->newDraft('yes');
		$control->setDraft($draft);
		$entry->control = $control;
	}
 
    $entryResult = $gdClient->insertEntry($entry, $uri);
    $idText = split('-', $entryResult->id->text);
    $newPostID = $idText[2];
    return $newPostID;
}


