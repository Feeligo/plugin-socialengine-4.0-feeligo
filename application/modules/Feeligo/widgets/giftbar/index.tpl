<script type="text/javascript">
  (function(){if(!this.flg){this.flg={};}this.flg.hello=function(){alert("Hello! Your user ID is <?php echo $this->viewer ? $this->viewer->user_id : '[null]'; ?>")}}).call(this);
</script>

<div id="flg_giftbar_container" class='flg'></div>

<?php if ($this->should_render_app): ?>
 
  <script src="<?php echo $this->app_loader_js_url; ?>"></script
  
<?php endif; ?>