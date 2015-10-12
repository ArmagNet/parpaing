<?php /*
	Copyright 2014-2015 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of Parpaing.

    Parpaing is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Parpaing is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Parpaing.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once("header.php");

?>
	<?php if ($isConnected) {?>
<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li class="active"><?php echo lang("breadcrumb_index"); ?></li>
	</ol>
	<div class="well well-sm">
		<p><?php echo lang("index_guide"); ?></p>
	</div>
</div>
	<?php } else {?>

<div class="container theme-showcase" role="main">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang("index_authentication_panel"); ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" id="loginForm">
				<fieldset>
					<div class="form-group">
						<label class="col-md-2 control-label" for="passwordInput"><?php echo lang("index_authentication_password"); ?></label>
						<div class="col-md-10">
							<input id="passwordInput" name="passwordInput" type="password" placeholder="<?php echo lang("index_authentication_password_placeholder"); ?>" class="form-control input-md" required="">
						</div>
					</div>
					<div class="form-group hidden renew-password">
						<label class="col-md-2 control-label" for="newPasswordInput"><?php echo lang("index_authentication_newpassword"); ?></label>
						<div class="col-md-10">
							<input id="newPasswordInput" name="newPasswordInput" type="password" placeholder="<?php echo lang("index_authentication_newpassword_placeholder"); ?>" class="form-control input-md">
						</div>
					</div>
					<div class="form-group hidden renew-password">
						<label class="col-md-2 control-label" for="confirmNewPasswordInput"><?php echo lang("index_authentication_confirm"); ?></label>
						<div class="col-md-10">
							<input id="confirmNewPasswordInput" name="confirmNewPasswordInput" type="password" placeholder="<?php echo lang("index_authentication_confirm_placeholder"); ?>" class="form-control input-md">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12 text-center">
							<button id="loginButton" type="submit" name="loginButton" class="btn btn-primary"><?php echo lang("common_authenticate"); ?></button>
							<button id="renewButton" type="button" name="renewButton" class="btn btn-default"><?php echo lang("common_change"); ?></button>
							<button id="resetButton" type="reset" name="resetButton" class="btn btn-inverse"><?php echo lang("common_reset"); ?></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<div class="container hidden padding-left-0" style="padding-right: 30px;">
		<?php echo addAlertDialog("defaultPasswordAlert", lang("defaultPasswordAlert"), "warning"); ?>
		<?php echo addAlertDialog("notSameNewPasswordAlert", lang("notSameNewPasswordAlert"), "warning"); ?>
		<?php echo addAlertDialog("badPasswordAlert", lang("badPasswordAlert"), "danger"); ?>
	</div>

</div>

	<?php }?>

<script type="text/javascript">
var userLanguage = '<?php echo SessionUtils::getLanguage($_SESSION); ?>';
</script>
<?php include("footer.php");?>
</body>
</html>