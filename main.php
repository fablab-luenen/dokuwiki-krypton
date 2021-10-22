<?php
if (!defined('DOKU_INC')) {
    die();
}
/* must be run from within DokuWiki */
@require_once dirname(__FILE__) . '/tpl_functions.php'; /* include hook for template functions */

$showTools = !tpl_getConf('hideTools') || (tpl_getConf('hideTools') && !empty($_SERVER['REMOTE_USER']));
$showSidebar = page_findnearest($conf['sidebar']) && ($ACT == 'show');
$showIcon = tpl_getConf('showIcon');
$showBackground = tpl_getConf('headerBackgroundImage');
?>
<!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>" dir="<?php echo $lang['direction'] ?>">
<!--
=========================================================
*  Argon Dokuwiki Template
*  Based on the Argon Design System by Creative Tim
*  Ported to Dokuwiki by Anchit (@IceWreck)
=========================================================
 -->
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
		<link rel="icon" type="image/png" href="assets/img/favicon.png">
		<title>
			<?php tpl_pagetitle()?> 
			[<?php echo strip_tags($conf['title']) ?>]
        </title>
		<?php tpl_metaheaders()?>
		<?php echo tpl_favicon(array(
			'favicon',
			'mobile',
		))
		?>

		<?php tpl_includeFile('meta.html')?>

		<!-- 
		I know the CSS and JS imports can be done within the style.ini and script.js files,
		but I had some issues with styling (and import order) there, so I'm doing those imports here. 
		-->
		<!--     Fonts and icons  -->
		<link href="<?php echo tpl_basedir(); ?>assets/css/fonts.css" rel="stylesheet">
		<!-- CSS Files -->
		<link href="<?php echo tpl_basedir(); ?>assets/css/doku.css" rel="stylesheet" />

		<!-- Conditional styles -->
		<style>
			<?php if($showBackground) { ?>
			header {
				background-image: url("<?php
					$backgroundUrl = tpl_getMediaFile(array(':header-background.svg', ':header-background.png',':wiki:header-background.svg', 'images/header-background.svg', ':wiki:header-background.png', ':header-background.svg', ':header-background.png', 'images/header-background.svg', 'images/header-background.png', /*fall back to logos*/ 'images/logo.svg', 'images/logo.png', ':wiki:logo.svg', ':wiki:logo.png', ':logo.svg', ':logo.png', ':wiki:dokuwiki-128.png'), false);
					echo($backgroundUrl);
			     ?>");
			}
			<?php } ?>
		</style>
	</head>

	<body class="docs ">
		<div id="dokuwiki__site">


			<header
				class="navbar navbar-horizontal navbar-expand navbar-dark flex-row align-items-md-center ct-navbar bg-primary py-2<?php if(showBackground) { echo(" backgroundImage"); }?>">

				<?php
				if ($showIcon) {
				?>
					<div class="header-title">
						<?php
						// get logo either out of the template images folder or data/media folder
						$logoSize = array();
						$logo = tpl_getMediaFile(array(':logo.svg', ':wiki:logo.svg', 'images/logo.svg', ':wiki:logo.png', ':logo.png', 'images/logo.png', ':wiki:dokuwiki-128.png'), false, $logoSize);
						// display logo and wiki title in a link to the home page
						tpl_link(
							wl(),
							'<img src="'.$logo.'" height="48px" alt="" /> <span>'.$conf['title'].'</span>',
							'accesskey="h" title="[H]"'
						);
						?>
					</div>
				<?php }else{?>
					<div class="btn btn-neutral btn-icon">
						<span class="btn-inner--icon">
							<!-- <i class=""></i> -->
						</span>
						<span
							class="nav-link-inner--text"><?php tpl_link(wl(), $conf['title'], 'accesskey="h" title="[H]"')?></span>

					</div>
				<?php }?>


				<div class="d-none d-sm-block ml-auto">
					<ul class="navbar-nav ct-navbar-nav flex-row align-items-center">
						<nav aria-label="Main Navigation" class="dropown"> 
							<ul>
								<li class="dropdown user_menu">
									<!-- aria-expanded needs managed with Javascript -->
									<button
										aria-expanded="false">
										<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="2 2 22 22" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
									</button>

									<ul>
										<?php if(!empty($_SERVER['REMOTE_USER'])): ?>
											<li class="user_info"><?php tpl_userinfo(); ?></li>
										<?php endif; ?>
										<?php foreach((new \dokuwiki\Menu\UserMenu())->getItems() as $item): ?>
											<li title="<?= $item->getTitle() ?>">
												<a href="<?= $item->getLink() ?>">
													<?= inlineSVG($item->getSvg()) ?>
													<?= $item->getTitle() ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</li>
							</ul>
						</nav>


						<li class="nav-item">
							<div class="search-form">
								<?php tpl_searchform()?>
							</div>
						</li>


					</ul>
				</div>
				<button class="navbar-toggler ct-search-docs-toggle d-block d-md-none ml-auto ml-sm-0" type="button"
					data-toggle="collapse" data-target="#ct-docs-nav" aria-controls="ct-docs-nav" aria-expanded="false"
					aria-label="Toggle docs navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

			</header>



			<div class="container-fluid">
				<div class="row flex-xl-nowrap">


					<?php
					// Render the content initially
					ob_start();
					tpl_content(false);
					$buffer = ob_get_clean();
					?>

					<!-- left sidebar -->
					<div class="col-12 col-md-3 col-xl-2 ct-sidebar">
						<nav class="collapse ct-links" id="ct-docs-nav">
							<?php if ($showSidebar): ?>
							<div id="dokuwiki__aside" class="ct-toc-item active">
								<a class="ct-toc-link">
									<?php echo $lang['sidebar'] ?>
								</a>
								<div class="leftsidebar">
									<?php tpl_includeFile('sidebarheader.html')?>
									<?php tpl_include_page($conf['sidebar'], 1, 1)?>
									<?php tpl_includeFile('sidebarfooter.html')?>
								</div>
							</div>
							<?php endif;?>
						</nav>
					</div>


					<!-- center content -->

					<main class="col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 ct-content dokuwiki" role="main">

						<div id="dokuwiki__top" class="site
						<?php echo tpl_classes(); ?>
						<?php echo ($showSidebar) ? 'hasSidebar' : ''; ?>">
						</div>

						<?php html_msgarea()?>
						<?php tpl_includeFile('header.html')?>


						<div class="d-flex justify-content-between align-items-center">
							<!-- Trace/Navigation -->
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<?php if ($conf['breadcrumbs']) {?>
									<div class="breadcrumbs"><?php tpl_breadcrumbs()?></div>
									<?php }?>
									<?php if ($conf['youarehere']) {?>
									<div class="breadcrumbs"><?php tpl_youarehere()?></div>
									<?php }?>
								</ol>
							</nav>

							<div class="argon-doku-page-menu">
								<?php
									// Check if the button should not be shown at all
									function isIrrelevant($item) {
										// Class names of buttons that should be shown directly on the page. Page source is deliberately omitted. 
										$irrelevant_items = array("top");

										if(in_array($item->getLinkAttributes('')['class'], $irrelevant_items)) {
											return true;
										}
										return false;
									}

									// Check if the button should be shown outside of the overflow menu or not
									function isImportant($item) {
										// Class names of buttons that should be shown directly on the page. Page source is deliberately omitted. 
										$important_items = array("edit", "show", "create", "draft");

										if(in_array($item->getLinkAttributes('')['class'], $important_items)) {
											return true;
										}
										return false;
									}

									// Get all available page menu items
									$menu_items = (new \dokuwiki\Menu\PageMenu())->getItems();
									$overflow_items = array();

									// Show the important items directly on the page
									foreach($menu_items as $item) {
										if(isImportant($item)) {
											$accesskey = $item->getAccesskey();
											$akey = '';
											if($accesskey) {
												$akey = 'accesskey="'.$accesskey.'" ';
											}				
											echo '<li class="'.$item->getType().'">'
												.'<a class="page-menu__link '.$item->getLinkAttributes('')['class'].'" href="'.$item->getLink().'" title="'.$item->getTitle().'" '.$akey.'>'
												.'<i class="">'.inlineSVG($item->getSvg()).'</i>'
												. '<span class="a11y">'.$item->getLabel().'</span>'
												. '</a></li>';
										} else if (!isIrrelevant($item)) {
											// Remember unimportant items for later
											array_push($overflow_items, $item);
										}
									}

									// Actually display them in an overflow menu
									if(!empty($overflow_items)) {
										echo '<li class="overflow"><a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></i></a><div class="dropdown-menu">';
										
										foreach($overflow_items as $item) {
											$accesskey = $item->getAccesskey();
											$akey = '';
											if($accesskey) {
												$akey = 'accesskey="'.$accesskey.'" ';
											}				
											echo '<a class="dropdown-item '.$item->getType().' page-menu__link '.$item->getLinkAttributes('')['class'].'" href="'.$item->getLink().'" title="'.$item->getTitle().'" '.$akey.'><i class="">'.inlineSVG($item->getSvg()).'</i><span>'.$item->getLabel().'</span></a>';
										}

										echo '</div></li></html>';
									}
								?>
							</div>
						</div>

						<hr />

						<!-- Wiki Contents -->
						<div id="dokuwiki__content">
							<div class="pad">

								<div class="page">

									<?php echo $buffer ?>
								</div>
							</div>							
						</div>

						<!-- Footer -->
						<hr />
						<footer class="footer-card">
							<div class="card-body">
								<div class="container">
									<div class="row">
										<div class="col">
											<div id="dokuwiki__footer">
												<div class="pad">
													<div class="doc">
														<?php tpl_pageinfo() /* 'Last modified' etc */ ?>
													</div>
													<?php tpl_license('0') /* content license, parameters: img=*badge|button|0, imgonly=*0|1, return=*0|1 */ ?>
												</div>
											</div>
										</div>
									</div>									
									<?php tpl_includeFile('footer.html') ?>
									<div class="site-tools">
										<?php
											$menu_items = (new \dokuwiki\Menu\SiteMenu())->getItems();
											foreach($menu_items as $item) {
												echo ''
													.'<a class="" href="'.$item->getLink().'" title="'.$item->getTitle().'">'
													. $item->getLabel()
													. '</a>';
											}
										?>
									</div>
								</div>
							</div>
						</footer>
						<?php tpl_indexerWebBug(); ?>
					</main>

					<!-- Right Sidebar -->
					<div class="d-none d-xl-block col-xl-2 ct-toc">
						<div>
							<?php tpl_toc()?>
						</div>
					</div>

				</div>
			</div>
		</div>
	</body>

</html>
