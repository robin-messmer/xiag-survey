<?php
	require 'DBHandler.php';

	//validate Link
	if( !isset( $_GET['link'] ) || strlen( $_GET['link'] ) < 13 || strlen( $_GET['link'] ) > 20 || !DBHandler::linkValid( $_GET['link'] ) ){
		echo "404 not found";
		exit;
	}
	
	//read poll info
	$poll = DBHandler::readPoll( $_GET['link']);
?>
<html>
	<?php require 'header.html'; ?>
	<body>
		<div id="poll" class="poll">
			<span v-show="hasVoted" >Thanks for voting<br/><br/><br/></span>
			
				<h1>
					<?php echo $poll["question"] ?>
					<br/><br/>
				</h1>
			<div v-show="!hasVoted">
				<div class="ex2-question">
					<div class="ex2-question__label">
						Your name:
					</div>
					<div class="ex2-question__input">
						<input type="text" v-model="uname" class="input-text">
					</div>
					<div class="ex2-question__answer" >
						<label v-for="(value, key) in answers">
							<input type="radio" v-model="picked" :value="key" name="do-we-go"/>{{key}}
						</label>
					</div>
					<div class="ex2-question__submit">
						<input @click="save()" type="submit" class="btn" value="Submit">
					</div>
				</div>
			</div>
			<h1>
				Results
			</h1>
			<br>
			<table class="ex2-table">
				<thead>
				<tr>
					<th>Name</th>
					<th v-for="(value, key) in answers">
					{{key}}
					</th>
				</tr>
				</thead>
				<tbody>
					<template v-for="(value, key) in answers">
						<tr v-for="( name ) in answers[key]">
							<td>{{ name }}</td>
							<td v-for="(answer, key2) in answers" >
								<span v-if="key == key2">x</span>
							</td>
						</tr>
					</template>
				</tbody>
			</table>
        </div>
		
		<script type="text/javascript">
			var comp = new Vue({
				el: '#poll',
				data: {
					//visitor's name
					uname: "",
					
					//the picked choice
					picked: "",
					
					//makes voting interface invisible if value equals true
					hasVoted: <?php echo ( DBHandler::hasVoted( $_GET['link'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ) ) ? "true" : "false"; ?>,
					
					//answers and voters' names
					answers: <?php echo $poll["answer_res"]; ?>
				},
				methods:{
					//save choice
					save: function(){
						if( this.uname.length < 2 || this.uname.length > 60 ){ alert("Enter valid name"); return; }
						if( this.picked == "" ){ alert("Pick a choice"); return; }

						$.ajax({
							url: "submitPoll.php",
							method: 'POST',
							data: {
								name: this.uname,
								answer: this.picked,
								link: "<?php echo $_GET['link']; ?>"
							},
							success: function(data) {
								this.hasVoted = true;
							}.bind(this)
						});
					},
					
					//add new voters to the result table
					add: function(answer, name){
						this.answers[answer].push(name)
					}
				}
			});
			
		/**Receive information about new voters in order to about the result table accordingly*/	
		 var pusher = new Pusher('aa6d547d659f02669d6c', {
			cluster: 'eu',
			forceTLS: true
		});

		var channel = pusher.subscribe('<?php echo $_GET['link']; ?>');
			channel.bind('event', function(data) {
			comp.add(data["message"][0], data["message"][1]);
		});
		/********************************************************/
		
		</script>
	</body>
</html>