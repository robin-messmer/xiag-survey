new Vue({
	el: '#pollCreation',
	data: {
		question: "",
		answers: ["", ""]
	},
	
	methods:{
		addAnswer: function(){
			this.answers.push("");
		},
		
		removeAnswer: function(){
			if( this.answers.length > 2 ){
				this.answers.splice(this.answers.length-1, 1)
			}
		},
		
		answersFilled: function(){
			for( var i = 0; i < this.answers.length; i++ ){
				if( this.answers[i] == "" ){ return false; }
			}
			
			return true;
		},
		
		answersTooLong: function(){
			for( var i = 0; i < this.answers.length; i++ ){
				if( this.answers[i].length > 30 ){ return true; }
			}
			
			return false;
		},
		
		createPoll: function(){
			if( this.question == "" ){ alert("Enter a question"); return;}
			if( !this.answersFilled() ){ alert("Fill out all answers"); return;}
			if( this.answersTooLong() ){ alert("Answer max. 30 Chars"); return;}
			if( this.question.length > 200 ){ alert("Question too long; max 200 chars"); return;}
			
			$.ajax({
				url: "createPoll.php",
				method: 'POST',
				data: {
					question: this.question,
					answers: this.answers
				},
				success: function(data) {
					window.location.href = "poll.php?link="+data;
				}
			});	
		}
	}
});