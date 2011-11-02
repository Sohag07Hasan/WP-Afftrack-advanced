<?php ?>
<div class="wrap">
				<?php screen_icon('link-manager'); ?>
				<h2>Add New Link</h2>
				<form name="addlink" id="addlink" method="post" action="test.php">
					<div id="poststuff" class="metabox-holder has-right-sidebar">
						<div id="post-body">
							<div id="post-body-content">
							
								<div id="namediv" class="stuffbox">
									<h3> <level for="link_name" >Name</level> </h3>
									<div class="inside">
										<input id="link_name" type="text" value="" tabindex="1" size="30" name="link_name" />
										<p>Example: Google, Yahoo</p>										
									</div>
								</div>
								
								<div id="addressdiv" class="stuffbox">
									<h3> <level for="link_address" >Web Address</level> </h3>
									<div class="inside">
										<input id="link_url" type="text" value="" tabindex="1" size="30" name="link_url" />
											<p>Example: <code>http://google.com, http://www.yahoo.com</code>
												&mdash; don’t forget the
												<code>http://</code> 
											</p>										
									</div>
								</div>
								
								<div  class="stuffbox">
									<h3> <level for="link_description" >Description</level> </h3>
									<div class="inside">
										<input id="link_description" type="text" value="" tabindex="1" size="30" name="link_description" />
											<p>Example: This is google homepage, or you can store any information 
											</p>										
									</div>
								</div>
								
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<div id="linkcategorydiv" class="postbox">									
										<div class="handlediv" title="Click to toggle">
											<br/>
										</div>
										<h3 class="hndle"><span>Categories</span></h3>
										<div class="inside">
											<select name="link_category">
												<option value="outbound">Outbound</option>
												<option value="click">Click</option>
												<option value="download">Download</option>
												<option value="goal">Goal</option>
											</select>
										</div>										
									</div>
								</div>
								
							</div>
						</div> <!-- poststuff  -->
						
						<!-- sidepanel is starting -->
						
						<div id="side-info-column" class="inner-sidebar">
							<div id="linkgoaldiv" class="postbox ">
								<div class="handlediv" title="Click to toggle"><br/></div>
								<h3 class="hndle"><span> Goal Set Up </span></h3>
								<div class="inside">
									<p>
										<label for="goal_id">Goal ID</label>
										<input type="text" name="goal_id" value="" />
									</p>
									<p>
										<label for="goal_revenue">Goal Revenue</label>
										<input type="text" name="goal_revenue" value="" />
									</p>
								</div>
							</div>
							<div id="linksubmitdiv" class="postbox ">
								<div class="handlediv" title="Click to toggle"><br/></div>
								<h3 class="hndle"><span> Save / Update </span></h3>
								<div class="inside">
									<input type="submit" value="Save" class="button-primary" />
								</div>
							</div>
							
						</div> <!-- side-info-column -->
						
					</div>				
				</form>
			</div>
			
			
