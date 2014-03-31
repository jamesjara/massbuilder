<!-- Begin Main Menu -->
<div class="navbar navbar-inverse navbar-fixed-top">
              <div class="navbar-inner">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand" href="#"><img src="<?php echo "uploads/".conf_domain.'/'.conf_logo;?>"  /></a></a>
                  <div class="nav-collapse collapse navbar-responsive-collapse">
				  <?php $RootMenu = new cMenu("RootMenu"); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(18, $Language->MenuPhrase("18", "MenuText"), "", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(31, $Language->MenuPhrase("31", "MenuText"), "mapingslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}mapings'), FALSE);
$RootMenu->AddMenuItem(33, $Language->MenuPhrase("33", "MenuText"), "fb_group_entrieslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}fb_group_entries'), FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "", 18, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "tools_translationlist.php?cmd=resetall", 8, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_translation'), FALSE);
$RootMenu->AddMenuItem(9, $Language->MenuPhrase("9", "MenuText"), "tools_backupslist.php?cmd=resetall", 8, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_backups'), FALSE);
$RootMenu->AddMenuItem(34, $Language->MenuPhrase("34", "MenuText"), "logslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}logs'), FALSE);
$RootMenu->AddMenuItem(32, $Language->MenuPhrase("32", "MenuText"), "toolslist.php?cmd=resetall", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools'), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "domainslist.php?cmd=resetall", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}domains'), FALSE);
$RootMenu->AddMenuItem(30, $Language->MenuPhrase("30", "MenuText"), "proyectoslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}proyectos'), FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "entrieslist.php?cmd=resetall", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}entries'), FALSE);
$RootMenu->AddMenuItem(29, $Language->MenuPhrase("29", "MenuText"), "", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "audittraillist.php", 29, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}audittrail'), FALSE);
$RootMenu->AddMenuItem(19, $Language->MenuPhrase("19", "MenuText"), "userslist.php", 29, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}users'), FALSE);
$RootMenu->AddMenuItem(20, $Language->MenuPhrase("20", "MenuText"), "userlevelpermissionslist.php", 29, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(21, $Language->MenuPhrase("21", "MenuText"), "userlevelslist.php", 29, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);

//$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
				 <ul class="nav pull-right">
                      <li class="divider-vertical"></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php if($Language->Phrase("opciones")!=null) echo $Language->Phrase("opciones"); else echo "Opciones";  ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
							<?php if (IsLoggedIn()){?>
							<li><a href="profile.php"><?php  if($Language->Phrase("perfil")!=null) echo $Language->Phrase("perfil"); else echo "Perfil";   ?></a></li>
							<li><a href="changepwd.php"><?php echo $Language->Phrase("ChangePwd");    ?></a></li>
							<?php } ?>
							<li class="divider"></li>
							<li class="nav-header"><?php if ($Language->Phrase("Language")!=null) echo $Language->Phrase("Language"); else echo "Idioma"; ?> </li>
						<li class="divider"></li>
                          <li><a href="ayuda.php"><?php if($Language->Phrase("ayuda")!=null) echo $Language->Phrase("ayuda"); else echo "Ayuda";   ?></a></li>
                          <li><a href="faq.php"><?php   if($Language->Phrase("ayuda")!=null) echo $Language->Phrase("Faq"); else echo "Faq";    ?></a></li>
                          <li class="divider"></li>
						  <li><a href="logout.php">Log Out</a></li>
                        </ul>
                      </li>					  
                      <li><a class="innosystem" target="_blank" href="http://www.innosystem.org"><span style="font-size: 15px;
color: rgb(185, 40, 21);font-weight: bold;" class="logo-red">
INNO</span><span style="color: rgb(180, 172, 172);font-weight: bold;font-size: 15px; " class="logo-white">SYSTEM
</span></a></li>
                    </ul>
                  </div><!-- /.nav-collapse -->
                </div>
              </div><!-- /navbar-inner -->
</div>
<!--</div>
 End Main Menu -->
