<?php
class cAction {
	protected $uniSyncUser;
	public function __construct() {
		$this->uniSyncUserId = 22;		// unisync user ID
		$sql = "SELECT * FROM `user` WHERE id = '{$uniSyncUserId}'";
		$this->uniSyncUser = mysql_fetch_array(mysql_query($sql));
		$this->getAction();	
	}

	public function getAction() {
		$ac = $_GET['type'];
		if($ac == 'register') {
			$this->regUser();
		} else if($ac == 'login') {
			$this->login();
		} else if($ac == 'FBlogin') {
			$this->FBlogin($_GET['email']);
		} else if ($ac == 'logout') {
			$this->logout();
		} else if ($ac == 'groupAdd') {
			$this->groupAdd();
		} else if ($ac == 'noteAdd') {    // nepouziva sa                                       
			$this->noteAdd($_GET['userId'], $_GET['groupId'], null, false, null); // nepouziva sa
		} else if ($ac == 'memberRequest') {
			$this->memberRequest($_GET['userId'], $_GET['groupId']);
		} else if ($ac == 'acceptRequest') {
			$this->acceptRequest($_GET['userId'], $_GET['groupId']);
		} else if ($ac == 'cancelRequest') {
			$this->cancelRequest($_GET['requestId']);
		} else if ($ac == 'groupEditInfo') {
			$this->groupEditInfo($_GET['groupId']);
		} else if ($ac == 'uploadGroupPhoto') {
			$this->uploadGroupPhoto($_GET['groupId']);
		} else if ($ac == 'groupEditTitle') {
			$this->groupEditTitle($_GET['groupId']);
		} else if ($ac == 'userEditTitle') {
			$this->userEditTitle($_GET['userId']);
		} else if ($ac == 'userEditInfo') {
			$this->userEditInfo($_GET['userId']);
		} else if ($ac == 'uploadUserPhoto') {
			$this->uploadUserPhoto($_GET['userId']);
		} else if ($ac == 'like') {
			$this->likeNote($_GET['userId'], $_GET['noteId'], true, $_GET['groupId']);
		} else if ($ac == 'dislike') {
			$this->likeNote($_GET['userId'], $_GET['noteId'], false, $_GET['groupId']);
		} else if ($ac == 'unlike') {
			$this->unlikeNote($_GET['userId'], $_GET['noteId'], true, $_GET['groupId']);
		} else if ($ac == 'undislike') {
			$this->unlikeNote($_GET['userId'], $_GET['noteId'], false, $_GET['groupId']);
		} else if ($ac == 'folderAdd') {
			$this->folderAdd($_GET['userId'], $_GET['groupId'], null);
		} else if ($ac == 'folderEdit') {
			$this->folderAdd($_GET['userId'], $_GET['groupId'], $_GET['folderId']);
		} else if ($ac == 'folderDel') {
			$this->folderDel($_GET['folderId'], $_GET['groupId']);
		} else if ($ac == 'noteVisualAdd') {
			$this->noteAdd($_GET['userId'], $_GET['groupId'], $_GET['folderId'], true, null);
		} else if ($ac == 'topicAdd') {
			$this->topicAdd($_GET['userId'], $_GET['groupId']);
		} else if ($ac == 'replyTopic') {
			$this->replyTopic($_GET['userId'], $_GET['topicId'], $_GET['groupId']);
		} else if ($ac == 'newsAdd') {
			$this->newsAdd($_GET['userId'], $_GET['groupId']);
		} else if ($ac == 'newsEdit') {
			$this->newsEdit($_GET['userId'], $_GET['groupId'], $_GET['novinka']);
		} else if ($ac == 'newsDel') {
			$this->newsDel($_GET['groupId'], $_GET['newsId']);
		} else if ($ac == 'editReply') {
			$this->editReply($_GET['replyId'], $_GET['groupId'], $_GET['topicId']);
		} else if ($ac == 'replyDel') {
			$this->replyDel($_GET['replyId'], $_GET['groupId'], $_GET['topicId']);    
		} else if ($ac == 'topicDel') {
			$this->topicDel($_GET['topicId'], $_GET['groupId']);    
		} else if ($ac == 'addComment') {
			$this->addComment($_GET['userId'], $_GET['groupId'], $_GET['newsId']);
		}  else if ($ac == 'editComment') {
			$this->editComment($_GET['commentId'], $_GET['groupId']);
		} else if ($ac == 'delComment') {
			$this->delComment($_GET['commentId'], $_GET['groupId']);
		} else if ($ac == 'fileAdd') {
			$this->fileAdd(null, $_GET['groupId'], $_GET['userId']);       // Pridavanie suboru mimo novinku! preto null
		} else if ($ac == 'editFileInfo') {
			$this->editFileInfo($_GET['groupId'], $_GET['fileId']);
		} else if ($ac == 'fileDelete') {
			$this->fileDelete($_GET['fileId']);
		} else if ($ac == 'noteDelete') {
			$this->noteDelete($_GET['noteId']);
		}  else if ($ac == 'noteEdit') {
			if (isset ($_GET['visual']) && $_GET['visual'] == 'true') $this->noteAdd($_GET['userId'], $_GET['groupId'], $_GET['folderId'], true, $_GET['noteId']);
			else $this->noteAdd($_GET['userId'], $_GET['groupId'], null, false, $_GET['noteId']);
		} else if ($ac == 'imgDel') {
			$this->imgDel($_GET['imgId']);
		} else if ($ac == 'kickMember') {
			$this->kickMember($_GET['memberId']);
		} else if ($ac == 'makeAdmin') {
			$this->makeAdmin($_GET['memberId']);
		} else if ($ac == 'addCategory') {
			$this->addCategory($_GET['groupId'], $_GET['userId']);
		} else if ($ac == 'delCategory') {
			$this->delCategory();
		} else if ($ac == 'leaveGroup') {
			$this->leaveGroup($_GET['userId'], $_GET['groupId']);
		} else if ($ac == 'editGroupMain') {
			$this->editGroupMain($_GET['groupId'], $_GET['userId']);
		} else if ($ac == 'editGroupInfo') {
			$this->editGroupInfo($_GET['groupId'], $_GET['userId']);
		} else if ($ac == 'changePassword') {
			$this->changePassword($_POST['oldPwd'], $_POST['pwd1'], $_POST['pwd2'], $_GET['userId']);
		} else if ($ac == 'userEditProfile') {
			$this->userEditProfile($_POST['name'], $_POST['surname'], $_POST['university'], $_POST['info'], $_GET['userId']);
		}    
	}
	
	public function userEditProfile($name, $surname, $university, $info, $userId) {
		$sql = "UPDATE `user` SET name = '{$name}',
								  surname = '{$surname}',
								  university = '{$university}',
								  info = '{$info}' WHERE id = {$userId}";
		$result = mysql_query($sql);
		if ($result) $_SESSION['action_ok'] = true; 
		else $_SESSION['action_err'] = true;
		Header('Location: '.$_SERVER['HTTP_REFERER']); 
	}
	
	public function changePassword ($oldPwd, $pwd1, $pwd2, $userId) {
		$sql = "SELECT password FROM `user` WHERE id = '{$userId}'";
		$tmp = mysql_fetch_array(mysql_query($sql));
		if ($oldPwd != $tmp['password']) 
			Header('Location: '.$_SERVER['HTTP_REFERER']. '&changePwd=err');
		else {
			if ($pwd1 != $pwd2)
				Header('Location: '.$_SERVER['HTTP_REFERER']. '&changePwd=err');
			else {
				$sql = "UPDATE `user` SET password = '{$pwd1}' WHERE id = {$userId}";
				$result = mysql_query($sql);
				if ($result) $_SESSION['action_ok'] = true; 
				Header('Location: '.$_SERVER['HTTP_REFERER']. '&changePwd=ok');  
			}
		}
	}
	
	public function editGroupInfo($groupId, $userId) {
		$sql = "UPDATE `group` SET info = '{$_POST['info']}',member_info = '{$_POST['member_info']}' WHERE id = {$groupId}";
		$result = mysql_query($sql);
		if ($result)
			Header("Location: index.php?page=group&id={$groupId}&show=settings&p=info");
	}
	
	public function editGroupMain($groupId, $userId) {
		$sql = "UPDATE `group` SET name = '{$_POST['name']}',university = '{$_POST['university']}',public = '{$_POST['privacy']}' WHERE id = {$groupId}";
		$result = mysql_query($sql);
		if ($result)
			Header("Location: index.php?page=group&id={$groupId}&show=settings");
	}
	
	public function leaveGroup($userId, $groupId) {
		$sql = "SELECT * FROM `member` WHERE id_user = {$userId}";
		$user = mysql_fetch_array(mysql_query($sql));
		$sql = "SELECT * FROM `member` WHERE id_group = {$groupId} AND admin = 1";
		$tmp = mysql_query($sql);
		$countAdmins = mysql_num_rows($tmp);
		$sql = "SELECT * FROM `member` WHERE id_group = {$groupId}";
		$tmp = mysql_query($sql);
		$countMembers = mysql_num_rows($tmp);
		if ($countAdmins == 1 && $user['admin'] == '1' && $countMembers != 1) {
			$_SESSION['leaveGroup'] = err;
			Header('Location: '.$_SERVER['HTTP_REFERER']);
			exit(1);
		} 
		if ($countMembers == 1) {  // MAZANIE SKUPINY
			$sql = "DELETE FROM `group` WHERE id = {$groupId}";
			$result2 = mysql_query($sql);
			$path = "./groups/{$groupId}";
			rrmdir($path);
		}
		$sql = "DELETE FROM `member` WHERE id_user = {$userId} AND id_group = {$groupId}";
		$result = mysql_query($sql);
		if ($result) {
			if ($result2) {
				Header('Location: index.php');
				exit(1);
			}
			Header('Location: '.$_SERVER['HTTP_REFERER']);
		}   
	} 
	
	public function delCategory() {
		$category = mysql_fetch_array(mysql_query("SELECT * FROM `category` WHERE name = '{$_POST['name']}'"));
		$sqlFiles = "SELECT * FROM `file` WHERE category = '{$_POST['name']}'";
		$resFiles = mysql_query($sqlFiles);
		while ($file = mysql_fetch_array($resFiles)) {
			$sqlFile = "UPDATE `file` SET category = 'nezaradené' WHERE id = '{$file['id']}'";
			mysql_query($sqlFile);
		}
		$sql = "DELETE FROM `category` WHERE name = '{$_POST['name']}'";                                        
		$result = mysql_query($sql);
		if ($result)
			Header('Location: '.$_SERVER['HTTP_REFERER']);
	}                                                   
	
	public function addCategory($groupId, $userId) {
		$sql = "SELECT * FROM `category` WHERE name = '{$_POST['parent']}' AND id_group = '$groupId'";
		$res = mysql_query($sql);
		if ($tmp = mysql_fetch_array($res)) {
			$parentId = $tmp['id'];
		} else $parentId = 0;
		$sql = "INSERT INTO `category` (`id`, `name`, `id_group`, `id_user`, `date`, `id_parent`) VALUES (NULL, '{$_POST['name']}', '{$groupId}', '{$userId}', NOW(), '{$parentId}');";
		$result = mysql_query($sql);
		if ($result)
			Header('Location: '.$_SERVER['HTTP_REFERER']);
	}
	
	public function makeAdmin($memberId) {
		$sql = "UPDATE `member` SET `admin` = 1 WHERE id = {$memberId}";
		$result = mysql_query($sql);
		if ($result)
			Header('Location: '.$_SERVER['HTTP_REFERER']);
	}
	
	public function kickMember($memberId) {
		$sql = "DELETE FROM `member` WHERE id = {$memberId}";
		$result = mysql_query($sql);
		if ($result)
			Header('Location: '.$_SERVER['HTTP_REFERER']);
	}
	
	public function imgDel($imgId) {
		$sql = "SELECT * FROM `image` WHERE id = {$imgId}";
		$img = mysql_fetch_array(mysql_query($sql));
		$unlink = unlink($img['path']);
		$unlinkThumb = unlink($img['path_thumb']);
		$sql = "DELETE FROM `image` WHERE id = {$imgId}";
		$result = mysql_query($sql);
		if ($unlink && $unlinkThumb && $result)
			Header('Location: ' . $_SERVER['HTTP_REFERER']);
	}     
	
	
	public function folderDel($folderId, $groupId) {
		$path = "./groups/{$groupId}/articles/{$folderId}";
		$newFld = mysql_fetch_array(mysql_query("SELECT * FROM `folder` WHERE id_user = '0' AND id_group = {$groupId}"));
		$sqlNotes = "SELECT * FROM `note` WHERE id_folder = '{$folderId}'";
		$res = mysql_query($sqlNotes);
		while ($note = mysql_fetch_array($res)) {
			$file = $path."/{$note['id']}n/"; 
			$newfile = "./groups/{$groupId}/articles/{$newFld['id']}/{$note['id']}n";
			$resultCopy = rename($file, $newfile);
			$sqlArticles = "UPDATE `note` SET `path` = '{$newfile}/{$note['id']}.json' WHERE id = '{$note['id']}'";
			$result = mysql_query($sqlArticles);
		} 
		$sqlArticles = "UPDATE `note` SET `id_folder` = '{$newFld['id']}' WHERE id_folder = '{$folderId}'";
		$result = mysql_query($sqlArticles);
		
		$sql = "DELETE FROM `folder` WHERE id = {$folderId}";
		$result2 = mysql_query($sql);
		$unlink = rrmdir($path);                 // zmaz so servera
		if ($result && $result2)
			Header("Location: index.php?page=group&id={$groupId}&show=folder");    
	}
	
	public function noteDelete($noteId) {
		$note = mysql_fetch_array(mysql_query("SELECT * FROM `note` WHERE id = {$noteId}"));
		$groupId = $note['id_group'];
		$unlink = unlink($note['path']);
		if ($note['visual'] == '1') {
		   rrmdir(dirname($note['path']));
		}
		$sql = "DELETE FROM `note` WHERE id = {$noteId}";
		$result = mysql_query($sql);
		if ($unlink && $result) {
			if($note['visual'] == '1')
				Header("Location: index.php?page=group&id={$groupId}&show=folder&idFolder={$note['id_folder']}");
			else
				Header("Location: index.php?page=group&id={$groupId}&show=notes");
		}
	}
	
	public function fileDelete($fileId) {
		$file = mysql_fetch_array(mysql_query("SELECT * FROM `file` WHERE id = {$fileId}"));
		$unlink = unlink($file['path']);
		$sql = "DELETE FROM `file` WHERE id = {$fileId}";
		$result = mysql_query($sql);
		if ($unlink && $result)
			Header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	public function editFileInfo($groupId, $fileId) {
		$sql = "UPDATE `file` SET `info` = '{$_POST['info']}' WHERE id = {$fileId}";
		$result = mysql_query($sql);
		if ($result)
			Header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	public function compress($source, $destination, $quality) { 
		$info = getimagesize($source); 
		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source); 
		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source); 
		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source); 
		imagejpeg($image, $destination, $quality); 
		return $destination; 
	}
	
	public function fileAdd($newsId, $groupId, $userId) {
		$dir = "./groups/{$groupId}/files/";
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}        
		$file_tmp = $_FILES['file']['tmp_name'];
		$file_name = $_FILES['file']['name'];
		$size = $_FILES["file"]["size"] / 1024;
		$quality = 100;
		/*while ($size / 1024 > 1) {
			$quality -= 5;
			$file_name = $this->compress($file_name, $file_name, $quality);
			$size = filesize($file_name / 1024);
		}*/
		$resultFile = move_uploaded_file($file_tmp,$dir.$file_name); 
		
		$path = mysql_real_escape_string($dir.$file_name); 
		if ($file_name != "" && $resultFile) {
			$sql = "INSERT INTO `file` "
				.  "(`id`, `id_news`, `id_group`, `path`, `size`, `id_user`, `info`, `category`, `date`) VALUES "
				.  "(NULL, '{$newsId}', '{$groupId}', '{$path}', '{$size}', '{$userId}', '{$_POST['info']}', '{$_POST['category']}', NOW());";
			$result = mysql_query($sql);
		} else $result = true;
		if ($result && $resultFile)
			Header('Location: ' . $_SERVER['HTTP_REFERER']);
		else echo mysql_error();
	}
	
	public function delComment($commentId, $groupId) {
		$sql = "DELETE FROM `comment` WHERE id = '{$commentId}'";
		$result = mysql_query($sql);
		if ($result)
			Header("Location: index.php?page=group&id={$groupId}&show=news");
	}
	
	public function editComment($commentId, $groupId) {
		$sql = "UPDATE `comment` SET `content` = '{$_POST['content']}', `date` = NOW() WHERE id = {$commentId}";
		$result = mysql_query($sql);
		if ($result)
			Header("Location: index.php?page=group&id={$groupId}&show=news");
	}
	
	public function addComment($userId, $groupId, $newsId) {
		$sql = "INSERT INTO `comment` "
			.  "(`id`, `id_user`, `id_news`, `content`, `date`, `id_group`) VALUES "
			.  "(NULL, '{$userId}', '{$newsId}', '{$_POST['content']}', NOW(), {$groupId});";
		$result = mysql_query($sql);
		if ($result)
			Header("Location: index.php?page=group&id={$groupId}&show=news&newsId={$newsId}");
	}
	
	public function topicDel($topicId, $groupId) {
		$sql = "DELETE FROM `topic` WHERE id = '{$topicId}'";
		$result = mysql_query($sql);
		$sqlReply = "DELETE FROM `reply` WHERE id_topic = '{$topicId}'";
		$result2 = mysql_query($sqlReply);
		if ($result && $result2)
			Header("Location: index.php?page=group&id={$groupId}&show=forum");
	}
	
	public function replyDel($replyId, $groupId, $topicId) {
		$sql = "DELETE FROM `reply` WHERE id = '{$replyId}'";
		$result = mysql_query($sql);
		if ($result)
			Header("Location: index.php?page=group&id={$groupId}&show=forum&topic={$topicId}");
	}
	
	public function editReply($replyId, $groupId, $topicId) {
		$sql = "UPDATE `reply` SET `content` = '{$_POST['content']}', `date` = NOW() WHERE id = {$replyId}";
		$result = mysql_query($sql);
		if ($result)
			Header("Location: index.php?page=group&id={$groupId}&show=forum&topic={$topicId}");
	}
	
	public function newsDel($groupId, $newsId) {
		$sql = "DELETE FROM `news` WHERE id = '{$newsId}'";
		$result = mysql_query($sql);
		if ($result) {
			Header("Location: index.php?page=group&id={$groupId}&show=news");
		}
	}
	
	public function newsEdit($userId, $groupId, $newsId) {
		$sql = "UPDATE `news` SET `content` = '{$_POST['content']}', `date` = NOW() WHERE id = {$newsId}";
		$result = mysql_query($sql);
		if ($result) {
			Header("Location: index.php?page=group&id={$groupId}&show=news#groupNews");
		}
	}
	public function makeHyperLinks($s) {
		return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
	}
	
	public function newsAdd($userId, $groupId) {
		$content = nl2br($_POST['content']);
		$content = $this->makeHyperLinks($content);
		
		$sql = "INSERT INTO `news` "
			.  "(`id`, `content`, `id_user`, `id_group`, `date`, `category`) VALUES "
			.  "(NULL, '{$content}', '{$userId}', '{$groupId}', NOW(), '{$_POST['category']}');";
		$result = mysql_query($sql);
		$row = mysql_fetch_row(mysql_query("SELECT max(id) FROM `web1`.`news`"));
		$newsId = $row[0];
		if ($result) {
			if (isset($_FILES['file']) && $_FILES['file']['name'] != "") {
				$this->fileAdd($newsId, $groupId, $userId);
			}
			else Header("Location: index.php?page=group&id={$groupId}&show=news");
		}
	}
	
	public function replyTopic($userId, $topicId, $groupId) {
		$content = nl2br($_POST['content']);         // zaistenie odriadkovania
		$sql = "INSERT INTO `reply` "
			.  "(`id`, `id_topic`, `id_user`, `content`, `date`, `id_group`) VALUES "
			.  "(NULL, '{$topicId}', '{$userId}', '{$content}', NOW(), {$groupId});";
		$result = mysql_query($sql);
		
		if($result) 
			Header("Location: ?page=group&id={$groupId}&show=forum&topic={$topicId}#topicReply"); 
	}
	
	public function topicAdd($userId, $groupId) {
		$sql = "INSERT INTO `topic` "
			.  "(`id`, `subject`, `id_user`, `id_group`, `date`, `category`) VALUES "
			.  "(NULL, '{$_POST['subject']}', '{$userId}', '{$groupId}', NOW(), '{$_POST['category']}');";
		$result = mysql_query($sql);
		
		$row = mysql_fetch_row(mysql_query("SELECT max(id) FROM `web1`.`topic`"));
		$topicId = $row[0];
		$content = nl2br($_POST['content']);         // zaistenie odriadkovania
		$sql2 = "INSERT INTO `reply` "
			.  "(`id`, `id_topic`, `id_user`, `content`, `date`) VALUES "
			.  "(NULL, '{$topicId}', '{$userId}', '{$content}', NOW());";
		$result2 = mysql_query($sql2);
		
		if($result && $result2) {
			Header("Location: ?page=group&id={$groupId}&show=forum&topic={$topicId}");
		}
	}
	
	public function userEditInfo($userId) {
		$sql = "UPDATE `user` SET info = '{$_POST['info']}' WHERE id = {$userId}";
		$result = mysql_query($sql);
		if ($result) $_SESSION['action_ok'] = true;
		Header("Location: index.php?page=home");      
	}
	
	public function folderAdd($userId, $groupId, $folderId) {
		if ($_POST['private']) {
			$public = 0;
		} else {
			$public = 1;
		}

		if ($folderId == null) {
			$sql = "INSERT INTO `folder` "
				 . "(`id`, `id_user`, `id_group`, `name`, `info`, `public`, `endinfo`, `date`) VALUES "
				 . "(NULL, '{$userId}', '{$groupId}', '{$_POST['name']}', '{$_POST['info']}', '{$public}', '{$_POST['endinfo']}', NOW());";
			$result = mysql_query($sql);
		  
			$row = mysql_fetch_row(mysql_query("SELECT max(id) FROM `web1`.`folder`"));
			$idFolder = $row[0];
		} else {
			$idFolder = $folderId;
			$sql = "UPDATE `folder` SET "
			   . "`name` = '{$_POST['name']}', `info` = '{$_POST['info']}', `public` = '{$public}', `endinfo` = '{$_POST['endinfo']}' "
			   . "WHERE id = {$idFolder};";
			$result = mysql_query($sql);
		}

		$path = "./groups/{$groupId}/articles/{$idFolder}";
		
		if(!is_dir($path)) {
			$result2 = mkdir($path);
		} else {
			$result2 = true;
		}

		$sql = "UPDATE `folder` SET `path` = '{$path}'";
		$result = mysql_query($sql);
		
		
		if ($folderId) {
				$sqlDelete = "DELETE FROM `folder_reference` WHERE id_folder = {$folderId}";
				mysql_query($sqlDelete); 
		} 
		   
		foreach($_POST['references'] as $ref) {
			if ($ref != null) {
				$sql = "INSERT INTO `folder_reference` (`id`, `id_folder`, `reference`) VALUES (NULL, '{$idFolder}', '{$ref}');";
				mysql_query($sql);
			}
		}  
		
		if($result) Header("Location: index.php?page=group&id={$groupId}&show=folder&idFolder={$idFolder}");
	}
	
	public function unlikeNote($userId, $noteId, $isUnlike, $groupId) {
		if ($isUnlike) $tmp = -1;  // jedna sa o unlike
		else $tmp = 1;             // jedna sa o undislike
		$sql = "DELETE FROM `likes` WHERE id_user = {$userId} AND id_note = {$noteId}";
		$result = mysql_query($sql);
		
		$sql = "UPDATE `note` SET likes = likes + {$tmp} WHERE id = {$noteId}";
		$result2 = mysql_query($sql);
		
		$sql = "UPDATE `user` SET rank = rank + {$tmp} WHERE id = {$userId}";
		$result3 = mysql_query($sql);
		
		if ($result && $result2 && $result3) header('Location: ' . $_SERVER['HTTP_REFERER']. '#showNoteInfo');
	}
	
	public function likeNote($userId, $noteId, $isLike, $groupId) {
		if ($isLike) $tmp = 1;  // jedna sa o like
		else $tmp = -1;         // jedna sa o dislike
		$sql = "INSERT INTO `likes` "
			 . "(`id`, `id_user`, `id_note`, `is_like`) VALUES "
			 . "(NULL, '{$userId}', '{$noteId}', '{$tmp}');";
		$result = mysql_query($sql);
		$sql = "UPDATE `note` SET likes = likes + {$tmp} WHERE id = {$noteId}";
		$result2 = mysql_query($sql);
		$sql = "UPDATE `user` SET rank = rank + {$tmp} WHERE id = {$userId}";
		$result3 = mysql_query($sql);
		
		if ($result && $result2 && $result3) {
			header('Location: ' . $_SERVER['HTTP_REFERER']. '#showNoteInfo');
		}
	}
	
	public function groupEditTitle($groupId) {
		$sql = "UPDATE `group` SET name = '{$_POST['name']}',university = '{$_POST['university']}' WHERE id = {$groupId}";
		$result = mysql_query($sql);
		if ($result) $_SESSION['action_ok'] = true;
			Header ("Location: index.php?page=group&id={$groupId}");
	}
	
	public function userEditTitle($userId) {
		$sql = "UPDATE `user` SET name = '{$_POST['name']}', surname = '{$_POST['surname']}', university = '{$_POST['university']}' WHERE id = {$userId}";
		$result = mysql_query($sql);
		if ($result) $_SESSION['action_ok'] = true;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	public function uploadGroupPhoto($groupId) {
		$path = "./groups/{$groupId}/";
		$uploadfile = $path . "groupPhoto.jpg";
		make_thumb($_FILES['photo']['tmp_name'], $uploadfile, 200, 150);
		$_SESSION['action_ok'] = true;
		Header("Location: index.php?page=group&id={$groupId}");
	}
	
	public function uploadUserPhoto($userId) {
		$path = "./users/{$userId}/";
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		} 
		$uploadfile = $path . "userPhoto.jpg";
		make_thumb($_FILES['photo']['tmp_name'], $uploadfile, 160, 120);
		$_SESSION['action_ok'] = true;
		Header("Location: index.php?page=home");
	}
	
	public function groupEditInfo($groupId) {
		$sql = "UPDATE `group` SET info = '{$_POST['info']}' WHERE id = {$groupId}";
		$result = mysql_query($sql);
		if ($result) $_SESSION['action_ok'] = true;
		Header("Location: index.php?page=group&id={$groupId}");
	}

	public function cancelRequest($requestId) {
		$sql = "DELETE FROM `member_request` WHERE id = {$requestId}";
		$result = mysql_query($sql);
		if ($result) {
			$_SESSION['cancelRequest'] = true;
		} else {
			$_SESSION['cancelRequest'] = false;
		}
		Header("Location: ".$_SERVER['HTTP_REFERER']);
	}
	
	public function acceptRequest($userId, $groupId) {
		$sql = "INSERT INTO `member` "
			 . "(`id`, `id_user`, `id_group`) VALUES "
			 . "(NULL, '{$userId}', '{$groupId}');";
		$result = mysql_query($sql);
		
		$sql = "DELETE FROM `member_request` WHERE id_user = {$userId} AND id_group = {$groupId}";
		$result2 = mysql_query($sql);
		
		if ($result && $result2) {
			$_SESSION['acceptRequest'] = true;
		} else {
			$_SESSION['acceptRequest'] = false;
		}
		Header("Location: index.php?page=group&id={$groupId}");
	}
	
	public function memberRequest($userId, $groupId) {
		$sql = "SELECT * FROM `member` WHERE id_user = '{$userId}' AND id_group = '{$groupId}'";
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result);
		if ($rows == 0) {
			$sql = "INSERT INTO `member_request` "
					. "(`id`, `id_user`, `id_group`) VALUES "
					. "(NULL, '{$userId}', '{$groupId}');";
			$result = mysql_query($sql);
			if ($result) 
				Header ("Location: index.php?page=group&id={$groupId}");
		}
	}                 
	
	public function noteJSONAdd($noteId, $userId, $groupId, $date, $keywords, $references, $content, $path, $folderId) {
		$note = array();
		$note = array('Id' => $noteId,
					'UserId' => $userId,
					'GroupId' => $groupId,
					'FolderId' => $folderId,
					'Date' => $date,
					'Title' => $keywords[0],
					'KeyWords' => $keywords,
					'References' => $references,
					'Content' => $content);         
		$fp = fopen($path, 'w');
		fwrite($fp, json_encode($note));
		fclose($fp);
		if ($fp == true) return true;
		else return false;  
	}        
	
	public function noteAdd($userId, $groupId, $folderId, $visual, $noteIdEdit) {
		if (!$noteIdEdit) {
			$sql = "INSERT INTO `web1`.`note` "
					. "(`id`, `id_user`, `id_group`, `date`) VALUES "
					. "(NULL, '{$userId}', '{$groupId}', NOW());";
			$result = mysql_query($sql);
			$row = mysql_fetch_row(mysql_query("SELECT max(id) FROM `web1`.`note`"));
			$noteId = $row[0];                            // Ziskanie ID note
		} else {
			$noteId = $noteIdEdit;
			$result = true;
		}  
		
		if (isset ($_POST['category'])) {
			mysql_query("UPDATE `note` SET `category` = '{$_POST['category']}' WHERE id = {$noteId}"); 
		}
		
		if ($visual) {
			$path = './groups/'.$groupId.'/articles/'.$folderId.'/'.$noteId.'n/';
			mysql_query("UPDATE `note` SET `visual` = 1 WHERE id = {$noteId}");
			mysql_query("UPDATE `note` SET `id_folder` = {$folderId} WHERE id = {$noteId}");
		} else {
			$path = './groups/'.$groupId.'/notes/';
		}
		if(!is_dir($path)) {
			mkdir($path, 0777, true);
		}
		
		if (!$noteIdEdit) {
			mysql_query("UPDATE `note` SET `path` = '{$path}{$noteId}.json' WHERE id = {$noteId}");
		}
		
		if($visual) {
			if (!is_dir($path."/thumbs/")) { 
			  mkdir($path."/thumbs/", 0777, true);                      // vytvorenie zlozky nahladov 
			} 
			$result3 = $this->uploadPhotos($path, $noteId);             // uploadPhotos
		} else {
			$result3 = true;
		}          
						
		$date = date("d. m. Y H:i:s");        
		$keywords = array();
		$i = 0;                
		foreach($_POST['keywords'] as $kw) {
			if ($kw != null) {  
				$keywords[$i] = $kw;
				$i++;                                
			}
		}
		// referencie
		$i = 0;
		$references = array();
		foreach($_POST['references'] as $ref) {
			if ($ref != null) {
				array_push($references, $ref);
			}
		}
		// obsah      
		$sql = "SELECT * FROM `image` WHERE id_note = {$noteId}";
		$tmp = mysql_query($sql); 
		$links = array();
		while($img = mysql_fetch_array($tmp)) {
			array_push($links, $img['path']);
		}
		$content = $_POST['content'];
		
		$count = substr_count($content, '[[');
		$search = '[[';
		$searchlen = strlen($search);
		$newstring = '';
		$offset = 0;
		for($i = 0; $i < $count; $i++) {
			if (($pos = strpos($content, $search, $offset)) !== false){
				if (($endOfNum = strpos($content, '|', $pos)) !== false) {
					$num = substr($content, $pos, $endOfNum-$pos);
					$num = substr($num, 2);
				}
				$newstring .= substr($content, $offset, $pos-$offset);
				if ($links[$num-1] != null)
					$newstring .= "<a href=\"{$links[$num-1]}\" class=\"fancybox\">";
				$offset = $pos + $searchlen + strlen($num)+1;
			}
		}
		$newstring .= substr($content, $offset);
		$newstring = str_replace(']]', '</a>', $newstring);
		if (!$visual) $newstring = nl2br($newstring);         // zaistenie odriadkovania pri poznamkach

		$content = $newstring;
			   
		$path = "{$path}{$noteId}.json";
		$result2 = $this->noteJSONadd($noteId, $userId, $groupId, $date, $keywords, $references, $content, $path, $folderId);        
		if ($result && $result2 && $result3) {
			if ($visual) {
				 Header ("Location: index.php?page=group&id={$groupId}&show=folder&idFolder={$folderId}&result=ok&showNote={$noteId}");
			}
			else Header ("Location: index.php?page=group&id={$groupId}&show=notes&result=ok&showNote={$noteId}");
		} else {
			Header ("Location: index.php?page=group&id={$groupId}&result=err");
		}        
	}
	
	public function uploadPhotos($dir, $noteId) {
		$tmp = true;
		foreach($_FILES['images']['tmp_name'] as $key => $tmp_name ){ 
			if($tmp_name != null) {                                          
				$file_name = $_FILES['images']['name'][$key];
				$file_tmp =$_FILES['images']['tmp_name'][$key];
				$file_type=$_FILES['images']['type'][$key];		           
				if (!move_uploaded_file($file_tmp,$dir.$file_name)) {
					echo "Error upload photo number: ".$key.".<BR>";
					$tmp = false;
				}
				chmod($dir.$file_name, 0755);
				make_thumb($dir.$file_name, $dir."thumbs/".$file_name, 160, 120);
				chmod($dir."thumbs/".$file_name, 0755);
				if ($noteId != null && $file_name != null) {
					$path = $dir.$file_name;
					$path_thumb = $dir."thumbs/".$file_name;
					$sql = "INSERT INTO `web1`.`image` "
					. "(`id`, `path`, `path_thumb`, `id_note`) VALUES "
					. "(NULL, '{$path}', '{$path_thumb}', '{$noteId}');";
					$result = mysql_query($sql);
				} 
			}
		}
		if ($tmp == true)
			return true;
		else 
			return false;
	}
	
	public function groupAdd() {
		if (isset($_SESSION['login']) && isset ($_POST)) {
			$sql = "INSERT INTO `web1`.`group` "                    // Vytvorenie skupiny
					. "(`id`,"
					. "`name`,"
					. "`university`,"
					. "`public`,"
					. "`member_info`,"
					. "`faculty`,"
					. "`info`) VALUES "
					. "(NULL, "
					. "'{$_POST['name']}',"
					. "'{$_POST['university']}',"
					. "'{$_POST['privacy']}',"
					. "'{$_POST['member_info']}',"
					. "'{$_POST['faculty']}',";
			$sql .= (isset ($_POST['info']) ? "'{$_POST['info']}');" : "NULL);");
			$result = mysql_query($sql);
		 
			$row = mysql_fetch_row(mysql_query("SELECT max(id) FROM `web1`.`group`"));
			$idGroup = $row[0];                            // Ziskanie ID skupiny

			$sql2 = "INSERT INTO `member` "                   // vytvorenie Admina
					. "(`id`,"
					. "`id_user`,"
					. "`id_group`, `admin`) VALUES "
					. "(NULL,"
					. "'{$_GET['idUser']}',"
					. "'{$idGroup}', '1')";
					
			$result2 = mysql_query($sql2);
			
			$path = './groups/'.$idGroup.'/';
			if(!is_dir($path)) {
				mkdir($path, 0777, true);
				mkdir('./groups/notes/', 0777, true);
			}
			
			$sqlFolder = "INSERT INTO `folder` 
						  (`id`, `id_user`, `id_group`, `name`, `info`, `public`,  `date`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$idGroup}', 'Rôzne', 'Všetky články bez zložky', '1', NOW())";
			$result3 = mysql_query($sqlFolder);
			$row = mysql_fetch_row(mysql_query("SELECT max(id) FROM `web1`.`folder`"));
			$idFolder = $row[0];                            // Ziskanie ID skupiny
			$path = './groups/'.$idGroup.'/articles/'.$idFolder.'/';
			if(!is_dir($path)) {
				mkdir($path, 0777, true);
			}
			
			$sqlc = "INSERT INTO `category` 
						  (`id`, `id_user`, `id_group`, `name`, `date`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$idGroup}', '1. ročník', NOW())";
			$result4 = mysql_query($sqlc);
			$sqlc = "INSERT INTO `category` 
						  (`id`, `id_user`, `id_group`, `name`, `date`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$idGroup}', '2. ročník', NOW())";
			$result5 = mysql_query($sqlc);
			$sqlc = "INSERT INTO `category` 
						  (`id`, `id_user`, `id_group`, `name`, `date`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$idGroup}', '3. ročník', NOW())";
			$result6 = mysql_query($sqlc);

			$defaultNewContent = "Vitaj v tvojej novej skupine na stránke universitysync.sk. 
								Skupina umožňuje zdieľať poznámky, súbory alebo novinky s tvojimi spolužiakmi. 
								Môžeš tiež využívať fórum ako prostriedok na vytvorenie nových tém pre riešenie tvojich otázok. 
								Administrátor skupiny môže prijímať nových záujemcov o členstvo v skupine, vyhadzovať členov skupiny, 
								vymazávať všetky údaje v skupine alebo spraviť adminom iného člena skupiny.";
			$sqlNews = "INSERT INTO `news` 
						  (`id`, `id_user`, `id_group`, `content`, `date`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$idGroup}', '{$defaultNewContent}', NOW())";
			$result7 = mysql_query($sqlNews);

			$defaultNotePath = "./defaultNote/defaultNote.json";
			$sqlNote = "INSERT INTO `note` 
						  (`id`, `id_user`, `id_group`, `path`, `date`, `id_folder`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$idGroup}', '{$defaultNotePath}', NOW(), '{$idFolder}')";
			$result8 = mysql_query($sqlNote);
			$note = mysql_fetch_array(mysql_query("SELECT id FROM `note` WHERE id_group = '{$idGroup}'"));
			$noteId = $note['id'];

			$defaultImg1Path = "./defaultNote/nice_girl_by_da1ly_fish-d5pkz5z.jpg";
			$defaultImg1PathThumb = "./defaultNote/thumbs/nice_girl_by_da1ly_fish-d5pkz5z.jpg";
			$sqlImg1 = "INSERT INTO `image` 
						  (`id`, `path`, `path_thumb`, `id_note`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$defaultImg1Path}', '{$defaultImg1PathThumb}', '{$noteId}')";
			$result9 = mysql_query($sqlImg1);

			$defaultImg2Path = "./defaultNote/nice_girl_by_da1ly_fish-d5pkz5z.jpg";
			$defaultImg2PathThumb = "./defaultNote/thumbs/nice_girl_by_da1ly_fish-d5pkz5z.jpg";
			$sqlImg2 = "INSERT INTO `image` 
						  (`id`, `path`, `path_thumb`, `id_note`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$defaultImg2Path}', '{$defaultImg2PathThumb}', '{$noteId}')";
			$result10 = mysql_query($sqlImg2);

			$defaultTopicSubject = "Na čo slúži fórum?";
			$sqlTopic = "INSERT INTO `topic` 
						  (`id`, `id_user`, `id_group`, `subject`, `date`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$idGroup}', '{$defaultTopicSubject}', NOW())";
	    	$result11 = mysql_query($sqlTopic);
	    	$topic = mysql_fetch_array(mysql_query("SELECT id FROM `topic` WHERE id_group = '{$idGroup}'"));
			$topicId = $topic['id'];

			$defaultReplyContent = "Tento priestor slúži na pýtanie sa otázok alebo vedenie diskusie na danú tému v rámci skupiny.
								 Témy vo fóre sú viditeľné a môžu na nich odpovedať len členovia skupiny.
								 Témy sa taktiež dajú zaradiť do konkrétnej kategórie alebo podkategórie.";
			$sqlReply = "INSERT INTO `reply` 
						  (`id`, `id_user`, `id_topic`, `content`, `date`) VALUES 
						  (NULL, '{$this->uniSyncUserId}', '{$topicId}', '{$defaultReplyContent}', NOW())";
		    $result12 = mysql_query($sqlReply);

			if ($result && $result2 && $result3 && $result4 && $result5 && $result6) {
				Header("Location: index.php?page=group&id={$idGroup}&result=ok");
			} else {
				header("Location: index.php?page=home&result=err");
			}
		}
	}
	
	/** vrati uzivatela s danym emailom a heslom ak existuje */
	public function findUser($email, $pwd) {
		$sql = "SELECT * FROM user WHERE user.email='".$email."'";
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result);
	  
		if ($rows == 0)
				  return false;
		else {
				  $user = mysql_fetch_array($result);
				  if ($user["password"] == $pwd) return $user;
		}
		  return false;
	}

	public function FBlogin($email) {
		if (!empty($email)) {
			$email = trim($email);
			$sql = "SELECT * FROM user WHERE user.email='".$email."'";
			$result = mysql_query($sql);
			if($user = mysql_fetch_array($result)) {
				session_start();
				$_SESSION['login'] = trim($_POST['email']);
				header('Location: index.php?page=home' );
			} else {
				$_SESSION['login_err'] = 1;
				header('Location: index.php?page=home&login=err');
			}
		}
	}
	
	public function login() {
		if (!empty($_POST['email']) && !empty($_POST['password'])) {
			$result = $this->findUser(trim($_POST['email']), trim($_POST['password']));
			if($result) {
				session_start();
				$_SESSION['login'] = trim($_POST['email']);
				header('Location: index.php?page=home' );
			} else {
				$_SESSION['login_err'] = 1;
				header('Location: index.php?page=home&login=err');
			}
		}
	}
	
	public function logout() {
		if($_SESSION['language'] == 1)
			$jazyk = "en";
		else $jazyk = "sk";
		session_destroy();
		header('Location: ./index.php?page=home&language='.$jazyk);
	}

	public function checkEmail($email) {
		$sql = "SELECT * FROM `user`";
		$result = mysql_query($sql);
		while ($user = mysql_fetch_array($result)) {
			if ($user['email'] == $email) {
				return true;
			}
		}
		return false;
	}

	public function regUser() {
		$err = false;
		$emailAlreadyExist = false;
		$emailAlreadyExist = $this->checkEmail($_POST['email']);
		if ($emailAlreadyExist || strlen($_POST['name']) > 31 || strlen($_POST['surname']) > 31 ||
			!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ||
			($_POST['password'] != $_POST['password1']) ||
			strlen($_POST['password']) > 18 || strlen($_POST['password']) < 6) $err = true; 
		if ($err == true) {
			if ($emailAlreadyExist) header ("Location: ./index.php?page=register&result=regErr&err=email");
			else header ("Location: ./index.php?page=register&result=regErr");
			return false;
		}
		$sql = "INSERT INTO user "
				. "(`id`,"
				. "`name`,"
				. "`surname`,"
				. "`email`,"
				. "`password`,"
				. "`university`,"
				. "`id_faculty`,"
				. "`info`) VALUES "
				. "(NULL,"
				. "'{$_POST['name']}',"
				. "'{$_POST['surname']}',"
				. "'{$_POST['email']}',"
				. "'{$_POST['password']}',"
				. "'{$_POST['university']}',"
				. "'{$_POST['faculty']}',"
				. "'{$_POST['info']}')";
				
		$result = mysql_query($sql);
		$row = mysql_fetch_row(mysql_query("SELECT max(id) FROM `user`"));
		$userId = $row[0];
		$path = "./users/{$userId}/";
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		} 
		if ($result) {
			session_start();
			$_SESSION['registerUserOk'] = true;
			$_SESSION['login'] = trim($_POST['email']);
			header('Location: index.php?page=home' );
			header ("Location: ./index.php?page=home");
		} else {
			header ("Location: ./index.php?page=register&result=regErr");
		}
	}
}
