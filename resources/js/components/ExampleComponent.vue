<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" id="login_form" style="margin-top:60px">
                    <div class="card-header">Login</div>
                    <br>
                    <div class="card-body">
                        <!-- <div id="login-alert" class="alert alert-danger" style="display:none" role="alert">
                            Wrong user name or password.
                        </div> -->
                        <input type="text" class="form-control" v-model="userName" placeholder="Please type your name....." required><br>
                        <input type="password" class="form-control" v-model="userPassword" placeholder="Please type your password....." required><br>
                        <button class="btn btn-primary form-control" @click="login">Login</button>
                    </div>
                </div>

                <div id="instruction" style="display:none;margin-top:60px" class="card">
                    <div class="card-header">Instruction</div>
                    <div class="card-body">
                        <div>
                            Welcome to car finder game.
                            After you start game you will see the image of car in the screen. where you need to answer what is the car brand
                            You will have one minute to answer each question.
                        </div>
                        <br>
                        <button class="btn btn-success form-control" @click="StartGame">Start Game</button>
                    </div>
                </div>

                <div id="questions" style="display:none;margin-top:60px" class="card">
                    <div class="card-header">Guess the car model</div>
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            Hello {{userName}} you current score is {{userScore}}
                        </div>
                        <div id="time-remain-alert" style="display:block" class="alert alert-danger" role="alert">
                             Time remain:&nbsp;{{countDown}} sec
                        </div>   
                        <!-- <div class="alert alert-success" role="alert">
                             <p>Guess the car model</p>
                        </div>                     -->                       
                        <img :src=question style="height:100px;weight:300px" class="imageproduit">                       
                        <br>
                        <br>
                        <div id="ans-submit" style="display:block">
                            <input type="text" class="form-control" v-model="user_ans" style="text-transform:lowercase" placeholder="Please type your answer here....." required><br>                            
                            <div id="empty-field-alert" class="alert alert-danger" style="display:none" role="alert">
                                Plese type the answer                         
                            </div>
                            <button class="btn btn-primary form-control" id="submit-btn" @click="Submit">Submit</button>
                        </div>                       
                        <br>
                        <div id="answer-alert" class="alert alert-danger" style="display:none" role="alert">
                            The correct answer is {{answer}} and you current score is {{userScore}}
                        </div>
                        <br>
                        <div id="game-over-alert" class="alert alert-danger" style="display:none" role="alert">
                            <h4 class="alert-heading">Game Over! ;)</h4>
                            <p>Aww , The correct answer is {{answer}} and you current score is {{userScore}}</p>
                            <hr>
                            <br>
                            <div style="float:right">
                                <button class="btn btn-outline-success" @click="StartAgain">Start Again</button>
                                <button class="btn btn-outline-primary"  @click="SubmitScore">Submit Score</button>
                            </div>
                            <br>
                            <br>
                        </div>
                        
                        <br>
                        
                    </div>
                </div>

                <div id="score-baord" style="display:none;margin-top:60px" class="card">
                    <div class="card-header" style="text:centre">Score Board</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Score</th>
                                <th scope="col">Start Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                </tr>
                                <tr v-for = "scores in scoreBoard" :key="scores.scoreBoard">
                                    <td>{{ scores.user_id }}</td>
                                    <td>{{ scores.name }}</td>
                                    <td>{{ scores.score }}</td> 
                                    <td>{{ scores.game_start_time }}</td>     
                                </tr>
                            </tbody>
                            </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data:function(){
            return{
                user_ans:'',
                info:null,
                question:null,
                answer:null,
                point:null,
                countDown : 60,
                question_bank:[1,2,3,4,5],
                userName:'riyal',
                userPassword:12345,
                userScore:0,
                scoreBoard:[],
                users:[],
                temporaryPass:'' ,
                gameStartTime:null,
                userID:''            
            }
        },
        mounted() {
            this.GetScoreBoard();
            var currentdate = new Date(); 
            this.gameStartTime = currentdate;
            console.log(this.gameStartTime);           
        },
        methods:{
            StartGame(){
                var instru = document.getElementById("instruction");
                var ques = document.getElementById("questions");
                if (instru.style.display === "none") {
                    instru.style.display = "block";
                    this.countDownTimer();
                }else {
                    instru.style.display = "none";
                    ques.style.display = "block";
                    this.countDownTimer();
                    this.GetQuestionBank();
                }
            },
            countDownTimer() {
                if(this.countDown > 0) {
                    setTimeout(() => {
                        this.countDown -= 1
                        this.countDownTimer()
                    }, 1000)
                }
            },
            GetQuestionBank(){
                const random = Math.floor(Math.random() * this.question_bank.length);             
                var request = new XMLHttpRequest();
                request.open('GET', 'http://127.0.0.1:8000/api/get_question/'+this.question_bank[random]);
                request.send();
                request.onload = ()=>{
                    console.log(JSON.parse(request.response));
                    this.info = JSON.parse(request.response);
                    this.question = this.info.data.image;
                    this.answer = this.info.data.answer;
                    this.point = this.info.data.point;
                }
            },
            Submit(){
                var emptyFieldAlert = document.getElementById("empty-field-alert");
                if(this.user_ans == '')
                {               
                    if (emptyFieldAlert.style.display === "none") {
                        emptyFieldAlert.style.display = "block";      
                    }else {
                        emptyFieldAlert.style.display = "none";                     
                    }
                }
                if(this.user_ans != '')
                {
                    emptyFieldAlert.style.display = "none";
                    var userTypedAnswer = this.user_ans.toLowerCase();
                    var result = this.answer.localeCompare(userTypedAnswer);
                    if(result == 0){
                        this.userScore += this.point;
                        this.user_ans = '';
                        this.GetAnotherQuestion();
                    }
                    else{
                        this.GameOver();
                        
                    }
                }
                
            },
            GetAnotherQuestion(){
                this.GetQuestionBank();
                this.countDown = 60;
            },
            SubmitScore(){
                var xhr = new XMLHttpRequest();
                var scoreBoard = document.getElementById("score-baord");
                var questionBoard = document.getElementById("questions");

                xhr.open("POST", "http://127.0.0.1:8000/api/submit_score");
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                }};
                var data = JSON.stringify({"name": this.userName,"user_id":this.userID,"game_start_time":this.gameStartTime, "score": this.userScore});
                xhr.send(data);
                this.GetScoreBoard();

                if(scoreBoard.style.display === "none") {
                    scoreBoard.style.display = "block";
                    questionBoard.style.display = "none";       
                }else {
                    scoreBoard.style.display = "none";
                    questionBoard.style.display = "block";                                               
                }

            },
            GetScoreBoard(){          
                var request = new XMLHttpRequest();
                request.open('GET', 'http://127.0.0.1:8000/api/get_score_board');
                request.send();
                request.onload = ()=>{
                    var scoreBoardData = JSON.parse(request.response);
                    this.scoreBoard = scoreBoardData.data;                   
                }
            },
            StartAgain(){
                this.userScore = 0;
                this.countDown = 60;
                var gameOverAlert = document.getElementById("game-over-alert");          
                var ansSubmitBtn = document.getElementById("ans-submit");
                var timeRemaining = document.getElementById("time-remain-alert");                                       
                
                if (gameOverAlert.style.display === "block") {
                    this.GetQuestionBank();
                    gameOverAlert.style.display = "none";
                    ansSubmitBtn.style.display = "block";
                    timeRemaining.style.display = "block";   
                }else {
                    gameOverAlert.style.display = "block";
                    ansSubmitBtn.style.display = "none";
                    timeRemaining.style.display = "none";                       
                }
                       
            },
                      
            GameOver(){
                    var gameOverAlert = document.getElementById("game-over-alert");         
                    var ansSubmitBtn = document.getElementById("ans-submit");
                    var timeRemaining = document.getElementById("time-remain-alert");                                        
                    
                    if (gameOverAlert.style.display === "none") {
                        gameOverAlert.style.display = "block";
                        ansSubmitBtn.style.display = "none";
                        timeRemaining.style.display = "none";        
                    }else {
                        gameOverAlert.style.display = "none";
                        ansSubmitBtn.style.display = "block";
                        timeRemaining.style.display = "block";                       
                    }
            },
            login(){
                var request = new XMLHttpRequest();
                request.open('GET', 'http://127.0.0.1:8000/api/users');
                request.send();
                request.onload = ()=>{
                    var userData = JSON.parse(request.response);
                    this.users = userData.data;

                    for(var i = 0; i < this.users.length; i++)
                    {
                        if(this.userName == this.users[i].name)
                        {                            
                            this.temporaryPass = this.users[i].password;
                            this.userID = this.users[i].id;
                            if(this.temporaryPass == this.userPassword)
                            {
                                console.log("sucessfully login");
                                
                                this.DashBoard();
                                break;
                            }else{       
                                console.log("Unable to login");                        
                            }
                        }
                        if(this.userName !== this.users[i].name){
                            //alert("Wrong username or password");                           
                        }
                    }                  
                }
            },
            GetUser(){
                return this.userName;
            },
            DashBoard(){
                var login = document.getElementById("login_form");
                var instru = document.getElementById("instruction");
                if (login.style.display === "none") {
                    instru.style.display = "block";      
                }else {
                    login.style.display = "none";
                    instru.style.display = "block";
                }
            }    
        },
        watch:{
            countDown:function(){
                if(this.countDown==0){                 
                this.GameOver();    
                    //this.StartGame();
                }
            }
        }
    }
</script>
