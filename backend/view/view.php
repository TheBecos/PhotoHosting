<?php
error_reporting(E_ERROR);

$rpath = realpath(__DIR__.'/../..');

include $rpath."/settings/config.php";
include $rpath."/settings/dbconnector.php";
include $rpath."/settings/auth.php";

require_once $rpath."/settings/class/Document.php";
require_once $rpath."/settings/class/Notify.php";
require_once $rpath."/settings/Control.php";

include $rpath."/settings/language/$Language.php";

$action = $_REQUEST['action'];
$id     = $_REQUEST['id'];

if ($action == 'document.view') {

	$types = \Document ::typesDocument();

	if ($id > 0) {

		$document         = \Document ::infoDocument($id);
		$document['type'] = $types[ $document['type'] ]['title'];
		$document['des']  = ($document['des'] == '') ? 'Без описания' : $document['des'];

	}

	?>
	<DIV class="zagolovok"><?= $language['DocumentView'] ?></DIV>

	<DIV id="formtabs" class="box--child">

		<?php
		if($document['comment']){

			$border = ($document['status'] == 1) ? "green-border" : "red-border";
			$color = ($document['status'] == 1) ? "green-important" : "red-important";
		?>
		<div class="flex-container mt20 mb10">

			<div class="flex-string wp100 fs-10 mb5 Bold uppercase hidden"><?= $language['Comment'] ?></div>
			<div class="flex-string wp100 material like-input <?=$border?>">

				<div class="like-input1"><?= nl2br($document['comment']) ?></div>
				<div class="label <?=$color?>"><i class="icon-chat <?=$color?>"></i><?= $language['Comment'] ?></div>

			</div>

		</div>
		<?php } ?>

		<div class="flex-container mb10 mt10 box--child">

			<div class="flex-string wp100 fs-10 mb5 Bold uppercase hidden"><?= $language['DocumentsPack'] ?></div>
			<div class="flex-string wp100 relative material like-input">
				<div class="Bold"><?= $document['title'] ?></div>
				<div class="label"><?= $language['DocumentsPack'] ?></div>
			</div>

		</div>

		<div class="flex-container mb10 box--child">

			<div class="flex-string wp100 fs-10 mb5 Bold uppercase hidden"><?= $language['DocumentType'] ?></div>
			<div class="flex-string wp100 relative material like-input">

				<div class="Bold"><?= $document['type'] ?></div>
				<div class="label"><?= $language['DocumentType'] ?></div>

			</div>

		</div>

		<div class="flex-container mt10 mb10">

			<div class="flex-string wp100 fs-10 mb5 Bold uppercase hidden"><?= $language['FilesUploaded'] ?></div>
			<div class="flex-string wp100 relative material like-input">

				<?php
				if (!empty($document['files'])) {

					$f = '';

					foreach ($document['files'] as $file) {

						$exti = yexplode(".", $file['name']);
						$ext  = texttosmall($exti[ count($exti) - 1 ]);

						$tip = (in_array($ext, [
							'jpg',
							'jpeg',
							'png',
							'gif'
						])) ? 'fileView' : 'fileLink';

						$f .= '
							<div class="infodiv bgwhite mb5 efile ha hand '.$tip.'" title="'.$language['View'].'" style="word-break: break-all" data-src="/upload/'.$fpath.$file['file'].'">
								<i class="'.$file['icon'].'"></i>'.$file['name'].' [ '.$file['size'].' кб ]
							</div>
						';

					}

					print '<div class="filexists like-input1 fs-10">'.$f.'</div>';

				}
				else
					print '<div class="filexists like-input1 fs-10 gray"><i class="icon-doc-alt gray3"></i>'.$language['NoFound'].'</div>';
				?>
				<div class="label"><?= $language['FilesUploaded'] ?></div>

			</div>

		</div>
		<div class="flex-container mt20 mb10">

			<div class="flex-string wp100 fs-10 mb5 Bold uppercase hidden"><?= $language['Description'] ?></div>
			<div class="flex-string wp100 material like-input">

				<div class="like-input1"><?= nl2br($document['des']) ?></div>
				<div class="label"><?= $language['Description'] ?></div>

			</div>

		</div>

	</DIV>

	<div class="button--pane text-right">

		<A href="javascript:void(0)" onClick="$document.edit('<?= $id ?>')" class="button"><i class="icon-pencil"></i><?= $language['Edit'] ?></A>&nbsp;
		<A href="javascript:void(0)" onClick="DClose()" class="button graybtn"><i class="icon-cancel-circled"></i><?= $language['Close'] ?></A>

	</div>
	<script>
		$('#dialog').css('width', '700px').center();
	</script>
	<?php

	exit();

}

if ($action == 'castomer.profile') {

	$profile = \Control ::castomerInfo($CustomerID);

	print json_encode_cyr(array(
		"profile" => ($profile['result']) ? $profile['result'] : null,
		"error"   => $profile['error']
	));

	exit();

}

if ($action == 'notify.view') {

	if ($id > 0) {

		$note = \Notify ::info($id);

		$d = \Notify ::edit($id, [
			"status" => 1
		]);

	}

	?>
	<DIV class="zagolovok"><?= $language['View'] ?></DIV>

	<DIV id="formtabs" class="box--child">

		<div class="flex-container mt20 mb10">

			<div class="flex-string wp100 material like-input">

				<div class="like-input1"><?= nl2br($note['content']) ?></div>
				<div class="label"><?= $language['Notify'] ?></div>

			</div>

		</div>

	</DIV>

	<div class="button--pane text-right">

		<A href="javascript:void(0)" onClick="DClose()" class="button graybtn"><i class="icon-cancel-circled"></i><?= $language['Close'] ?>
		</A>

	</div>
	<script>
		$('#dialog').css('width', '700px').center();
		$notify.popup();
	</script>
	<?php

	exit();

}