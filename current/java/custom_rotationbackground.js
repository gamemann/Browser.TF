/**
*	Name: custom_rotationbackground.js
*	Author: Christian Deacon (Roy)
*	Note: Rotates background images.
*	Date: 7-13-16
**/

/* Global variables. */
var g_bRotateInProgress = false;
var g_iLastRotateNumber = 0;
var g_bFirstRotateTime = true;

/* Configurable variables. */
var g_iFadeOutTime = 1.2;	/* Seconds. */
var g_iFadeInTime = 1.2;	/* Seconds. */
var g_iTimer = 10.0			/* Seconds. */

/**
*	Name: rotateBackground();
*	Description: Rotates the background.
*	Sub-functions: callback_FirstFade(), callback_secondFade().
**/
function rotateBackground()
{
	/* A rotation is in progress, set this variable to true! */
	g_bRotateInProgress = true;
	
	/* First, fade out the background. */
	$('#background').fadeTo((g_bFirstRotateTime) ? 0 : g_iFadeOutTime * 1000, 0.0, callback_FirstFade);
}

/**
*	Name: callback_FirstFade();
*	Description: Called when the first FadeTo is complete.
*	@return null
**/
function callback_FirstFade()
{
	/* Get a random number between 1 and 9. */
	var i = Math.floor(Math.random() * (9 - 1 + 1)) + 1;
	
	/* Ensure the last number isn't the same as the new number. */
	var iAttempts = 0;
	
	while (g_iLastRotateNumber == i)
	{
		i = Math.floor(Math.random() * (9 - 1 + 1)) + 1;
		
		iAttempts++;
		
		if (iAttempts > 5)
			break;
	}
	
	/* Copy the global rotation number. */
	g_iLastRotateNumber = i;
	
	/* Set the background image. */
	$('#background').css('background', 'url(\'backgrounds/' + i + '.jpg\')');
	
	/* Fade back in. */
	$('#background').fadeTo(g_iFadeInTime * 1000, 1.0, callback_SecondFade);
}

/**
*	Name: callback_SecondFade();
*	Description: Called when the last FadeTo is complete.
*	@return null
**/
function callback_SecondFade()
{
	if (g_bFirstRotateTime)
	{
		g_bFirstRotateTime = false;
	}
	
	/* Rotation is no longer in progress. Set the variable back to false. */
	g_bRotateInProgress = false;
}

/* Call the first rotate background. */
$(document).ready(function()
{
	if (!g_bRotateInProgress)
	{
		rotateBackground();
	}
});

/* Set timer to rotate between background images. */
setInterval(rotateBackground, g_iTimer * 1000);