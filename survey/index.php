<html>
	<?php require 'header.html'; ?>
	<body>
	
	<div id="pollCreation" class="poll">
            <table class="poll-table">
                <thead>
                <tr>
                    <th>Question:</th>
                    <th>
                        <input type="text" v-model="question" placeholder="Where do we go out tonight?" class="input-text">
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(answer, index) in answers">
                    <th>Answer {{index+1}}:</th>
                    <td>
                        <input type="text" v-model="answers[index]" class="input-text">
                    </td>
                </tr>
                <tr>
                    <td @click="addAnswer()" class="poll-table__plus">
                        <button class="btn btn--plus">
                            +
                        </button>
                    </td>
					<td @click="removeAnswer()" class="poll-table__plus">
                        <button class="btn btn--plus">
                            -
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <button @click="createPoll()" class="btn btn--start">
                Start
            </button>
        </div>
	
	
	<script type="text/javascript" src="js/pollCreation.js"></script>
	</body>
</html>