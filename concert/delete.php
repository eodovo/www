<?
   session_start();

   @extract($_POST);
	@extract($_GET);
	@extract($_SESSION);
   
   include "../lib/dbconn.php";
   if(!$scale){
	$scale=10;	
	}						// 한 화면에 표시되는 글 수

    if ($mode=="search")
	{
		if(!$search)
		{
			echo("
				<script>
				 window.alert('검색할 단어를 입력해 주세요!');
			     history.go(-1);
				</script>
			");
			exit;
		}

		$sql = "select * from $table where $find like '%$search%' order by num desc";
	}
	else
	{
		$sql = "select * from $table order by num desc";
	}

   //삭제 시에는 글뿐만 아니라, 서버에 저장된 이미지도 모두 삭제되어야 함

   $sql = "select * from $table where num = $num";
   $result = mysql_query($sql, $connect);

   $row = mysql_fetch_array($result);

   $copied_name[0] = $row[file_copied_0];
   $copied_name[1] = $row[file_copied_1];
   $copied_name[2] = $row[file_copied_2];

   for ($i=0; $i<3; $i++)
   {
		if ($copied_name[$i]) //첨부된 파일 있으면
	   {
			$image_name = "./data/".$copied_name[$i]; //경로설정 '.data/2022_11_21_10_20_15_0.jpg'
			unlink($image_name); //서버에 있는 이미지파일을 삭제해주는 메소드;
	   }
   }

   $sql = "delete from $table where num = $num";
   mysql_query($sql, $connect);

   mysql_close();

   echo "
	   <script>
	    location.href = 'list.php?table=$table';
	   </script>
	";
?>

