<?php
session_start();
$file = "./assets/csvs/tweets_".$_SESSION['user_screen_name'].".csv";
$csv_handler = fopen ($file,'w');
fwrite ($csv_handler,"djklf===jds\ndgahdghj");
fclose ($csv_handler);