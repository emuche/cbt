
			<div class="modal fade" id="loginpopup">
			<div class="modal-dialog">
				<div class="modal-content">

					<!--header-->
					<div class="modal-header">
						<button  type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">Log In</h3>
					</div>


					<!--body (form)-->
					<div class="modal-body text-center" style="padding-bottom: 0px;" >
						<form role="form" action="login.php" method="post">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Username" name="username">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" placeholder="Password" name="password">
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
									<label > <input type="checkbox" name="remember" > Remember Me</label>
								</div>
								<div class="col-sm-6">
									<label for="recover"><a href="recover.php" class="recover" id="recover" style="color: #333; text-decoration: none;">Forgot Password?</a></label>
								</div>
							</div>
						
					</div>
						<!--button-->
						<div class="modal-footer">
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-primary btn-block">Log In</button>
							
						</div>
					</form>
				</div>
			</div>
		</div>