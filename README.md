# php-pastefs-post
PasteFS Content Posting using PasteFS API using PHP

usage:
```html
<?php
include_once('pastefs.php');

$pastefs = new PasteFS('API_KEY');
$pastefs->setText("text that needs to be posted goes here");
$pasteInfo = $pastefs->post();

$pasteInfo = json_decode( $pasteInfo );
$pasteIdInfo = json_decode( base64_decode( $pasteInfo->packinfo ));

$pid = $pasteIdInfo->pid; // global pid
$ppid = $pasteIdInfo->ppid; // personal pid

```
