<?php

session_start();
if (!isset($_SESSION['username']))
{
    header('location: login.php');
}

?>





<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="ballfall-style.css">
	<title>Catch the ball</title>
</head>
<body>
<canvas id="canvas" height="300" style="border: 1px solid black;
  border-bottom: 2px solid brown">Hey canvas is not supported</canvas>
<script>
    const canvas = document.getElementById('canvas')
    const ctx = canvas.getContext('2d');

    const canvasH = canvas.height
    const canvasW = canvas.width

    let ball = { x: 150,y: 0,w: 20};
    let platformH = 10
    let platformW = canvasW
    let plDiff = 50
    let plSpeed = 1
    let leftPressed = (rightPressed = false);
    let moveSpeed = 2;
    let interval = (scoreInterval = null);
    let score = 0;
    let gravity = 0.2;
    let dropSpeed = 1;

    function randHoleX(){
    	return Math.floor(Math.random() * 270);
    }

    console.log(randHoleX());

    let platforms = [{ x: 0,y: canvasH,holeX: randHoleX(),holeW: 20 }
     ];
    drawBall()
    drawPlatforms()
    movePlatforms()
    navigateBall()
    drawScore()

    scoreInterval = setInterval(() => {
      score++;
    },1000);

    function movePlatforms() {

        let count = 0
        if (interval) return;
        interval = setInterval(()=>{

            checkGameOver()
            if(count == Math.floor(plDiff / plSpeed))
            { 
              if (platforms.length > 10) {
                platforms.splice(0,4);
               }     
              addNewPlatform()
              count = 0;
           }
            
           platforms.forEach((pl) => (pl.y -= plSpeed));

           const closest = platforms.find(pl => ball.y < pl.y+10 && ball.y > pl.y - ball.w);
           if (closest) {
           holdAndDrop(closest)
           }
           else{
            dropSpeed = dropSpeed + gravity;
            ball.y += dropSpeed;
           }

           ctx.clearRect(0,0,canvasW,canvasH)
           drawPlatforms();
           drawBall();
           drawScore();
           count++;

           console.log(platforms.length);
        },20);
    }

    function checkGameOver() {
       if (ball.y < 0) {
        alert("Game Over !! Your Score is " + score);
        reset()
       }
    }

    function reset() {
        ball = { x: 150,y: 0,r: 5 };
        platforms = [{ x: 0,y: canvasH,holeX: randHoleX(),holeW: 20 }];
        clearInterval(interval);
        clearInterval(scoreInterval);
        interval = scoreInterval = null;
        movePlatforms();
        
    }
    function addNewPlatform() {
        const lastPlatform = platforms[platforms.length - 1];
        platforms.push({ 
            x: 0,
            y: lastPlatform.y + plDiff,
            holeX: randHoleX(),
            holeW: 20 
        });
    }

    function drawPlatforms() {
    	platforms.forEach(pl => {
    		createPl(pl)
    		createHole(pl)
    	});

       function createHole(pl) {
    	ctx.beginPath()
    	ctx.rect(pl.holeX,pl.y,pl.holeW,platformH)
    	ctx.fillStyle = "white"
    	ctx.fill()
    	ctx.closePath()
    	}

       function createPl(pl) {
    	ctx.beginPath()
    	ctx.rect(pl.x,pl.y,platformW,platformH)
    	ctx.fillStyle = "black"
    	ctx.fill()
    	ctx.closePath()
    	}
    }

    function holdAndDrop(closest) {

        if(ball.y > closest.y - ball.w){
            if (ball.x > closest.holeX && ball.x < closest.holeX + closest.holeW)
            {
                ball.y += 1;
            }else{
             ball.y = closest.y - ball.w;
         }
        } 
    }

    function drawBall() {

        // Navigation
        if (leftPressed && ball.x - ball.w > 0) {
            ball.x -= moveSpeed;
        }
        if (rightPressed && ball.x + ball.w < canvasW) {
            ball.x += moveSpeed;
        }

    	ctx.beginPath()
        const img = new Image()
        img.src = "C:/Users/HP/Desktop/game project/fly1.png";
    	ctx.drawImage(img, ball.x, ball.y, ball.w, ball.w);
    	ctx.closePath()
    }

    function drawScore() {
        ctx.beginPath()
        //ctx.fillStyle = "black"
        ctx.fill()
        ctx.fillText("Score: " + score,10,10)
        ctx.closePath()
    }

    function navigateBall() {
        document.addEventListener('keydown',(e) => {
            if (e.getModifierState("Alt")) {
              if (e.key === 'ArrowLeft') {
              moveSpeed = 10;
            }
            if (e.key === 'ArrowRight') {
              moveSpeed = 10;
            }
           }
            
        if (e.key === 'ArrowLeft') {
            leftPressed = true;
            }
        if (e.key === 'ArrowRight') {
              rightPressed = true;
            }
        });
        document.addEventListener('keyup',(e) => {
            if (e.key === 'ArrowLeft') {
              leftPressed = false;
            }
            if (e.key === 'ArrowRight') {
              rightPressed = false;
            }
            moveSpeed = 2;
        })
    }

</script>
<div style="border: none;" class="link"><a href="logout.php">Log Out</a></div>
</body>
</html>