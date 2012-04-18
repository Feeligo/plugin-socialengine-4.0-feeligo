<?php
$response = $this->json;
if (isset($this->callback) && $this->callback!==null && strlen($this->callback) > 0) {
  $response = $this->callback."(".$this->json.");";
}
?>
<?php echo $response; ?>