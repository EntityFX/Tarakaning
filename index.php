<?php

  require_once "engine/classes/UsersController.php";
  $f=new UsersController();
  $f->createUser("EntityFX1","1",0,"Артём","Солопий","Валерьевич","t@ym_s@mail.ru");
  echo "Tarakaning.php";
?>
