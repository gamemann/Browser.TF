/* Custom JavaScript code goes in here... */
// Browser.tf's custom JavaScript file.
var fullServerTable;
var minAmount = 1;
var maxAmount = 10;
var serversMethod = 0;	// Server's method.


var iGameID = 440;
getGameID();

// Open Server
function OpenServer(ip)
{
	window.open('steam://connect/' + ip, 'none');
}

// Favorite
function favoriteServer(ip, port, noText, id)
{
	$.get('/scripts/favoriteServer.php?ip=' + ip + '&port=' + port + '&action=favorite', function (Data)
	{
		if (Data == 1)
		{
			// Success!
			var text = "";
			
			if (noText)
			{
				text = "";
			}
			else
			{
				text = " Un-Favorite";
			}
			
			$('#favoriteItem-' + id).html('<span class="glyphicon glyphicon-star custom-unfav-star" title="Un-Favorite" onclick="unFavoriteServer(\'' + ip + '\', ' + port + ', ' + noText + ', ' + id + ');"></span>' + text);
		}
	});
}

function unFavoriteServer(ip, port, noText, id)
{
	$.get('/scripts/favoriteServer.php?ip=' + ip + '&port=' + port + '&action=unfavorite', function (Data)
	{
		console.log(Data);
		if (Data == 1)
		{	
			// Success!
			var text = "";
			
			if (noText)
			{
				text = "";
			}
			else
			{
				text = " Favorite";
			}
			
			$('#favoriteItem-' + id).html('<span class="glyphicon glyphicon-star custom-fav-star" title="Favorite" onclick="favoriteServer(\'' + ip + '\', ' + port + ', ' + noText + ', ' + id + ');"></span>' + text);
		}
	});
}

// Admin Panel (Accept/Deny Server Request)
function addRequest(id, serverIP, serverPort)
{
	var notes = $('#' + id + '-notes').val();
	
	$.get('/scripts/request.php?id=' + id + '&ip=' + serverIP + '&port=' + serverPort + '&notes=' + notes + '&action=add', function (Data)
	{
		if (Data == 1)
		{	
			// Change the class.
			$('#' + id + '-request-row').addClass('status-approved');
			
			// Change the actions
			$('#' + id + '-request-action').html('---');
			
			// Change the notes row.
			$('#' + id + '-request-notesrow').html(notes);
		}
		else
		{
			alert(Data);
		}
	});
}

function removeRequest(id, serverIP, serverPort)
{
	var notes = $('#' + id + '-notes').val();
	
	$.get('/scripts/request.php?id=' + id + '&ip=' + serverIP + '&port=' + serverPort + '&notes=' + notes + '&action=remove', function (Data)
	{
		if (Data == 1)
		{	
			// Change the class.
			$('#' + id + '-request-row').addClass('status-unapproved');
			
			// Change the actions
			$('#' + id + '-request-action').html('---');
			
			// Change the notes row.
			$('#' + id + '-request-notesrow').html(notes);
		}
	});
}

// Admin Panel (Promote/Demote users)
function promoteUser(id)
{	
	$.get('/scripts/user.php?id=' + id + '&action=promote', function (Data)
	{
		if (Data == 1)
		{				
			// Change the action.
			$('#' + id + '-users-action').html('<span class="glyphicon glyphicon-star custom-unfav-star-users" title="Demote" onclick="demoteUser(' + id + ');"></span>');
		}
	});
}

function demoteUser(id)
{	
	$.get('/scripts/user.php?id=' + id + '&action=demote', function (Data)
	{
		if (Data == 1)
		{	
			// Change the action.
			$('#' + id + '-users-action').html('<span class="glyphicon glyphicon-star custom-fav-star-users" title="Promote" onclick="promoteUser(' + id + ');"></span>');
		}
	});
}

// Admin Panel (Enable/Disable Server)
function disableServer(id, ip, port)
{
	$.get('/scripts/toggleServer.php?ip=' + ip + '&port=' + port + '&action=disable', function (Data)
	{
		if (Data == 1)
		{	
			// First, change the action.
			$('#' + id + '-action').html('<span class="glyphicon glyphicon-ok-circle custom-enable" style="cursor: pointer;" title="Enable" onclick="enableServer(' + id + ', \'' + ip + '\', ' + port + ');"></span>');
			
			// Next, add the classname to the row.
			$('#' + id + '-row').addClass('server-disabled');
		}
	});
}

function enableServer(id, ip, port)
{
	$.get('/scripts/toggleServer.php?ip=' + ip + '&port=' + port + '&action=enable', function (Data)
	{
		if (Data == 1)
		{	
			// First, change the action.
			$('#' + id + '-action').html('<span class="glyphicon glyphicon-remove-circle custom-disable" style="cursor: pointer;" title="Disable" onclick="disableServer(' + id + ', \'' + ip + '\', ' + port + ');"></span>');
			
			// Next, add the classname to the row.
			$('#' + id + '-row').removeClass('server-disabled');
		}
	});
}

function copyLink()
{
	var $temp = $('<input>');
	$('body').append($temp);
	$temp.val('http://browser.tf?url=' + currentContent).select();
	document.execCommand('copy');
	$temp.remove();
}

function insertParam(key, value)
{
    key = encodeURI(key); value = encodeURI(value);

    var kvp = document.location.search.substr(1).split('&');

    var i=kvp.length; var x; while(i--) 
    {
        x = kvp[i].split('=');

        if (x[0]==key)
        {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if(i < 0) 
	{
		kvp[kvp.length] = [key,value].join('=');
	}

    document.location.search = kvp.join('&');	
}

// Custom filters
// Empty
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		if(!fullServerTable)
		{
			// Not the correct table. Ignore filter.
			return true;
		}
		
        var empty = $('#hideserversempty').prop("checked");
		
        var players = parseFloat( data[1] ) || 0;
        var maxplayers = parseFloat( data[2] ) || 0;
		
        if ((!empty) || (empty && players > 0))
        {
            return true;
        }
		
        return false;
    }
);

// Full
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		if(!fullServerTable)
		{
			// Not the correct table. Ignore filter.
			return true;
		}
		
        var full = $('#hideserversfull').prop("checked");
		
        var players = parseFloat( data[1] ) || 0;
        var maxplayers = parseFloat( data[2] ) || 0;
		
        if ((!full) || (full && players < maxplayers))
        {
            return true;
        }
		
        return false;
    }
);

// > 24 Max Players
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		if(!fullServerTable)
		{
			// Not the correct table. Ignore filter.
			return true;
		}
		
        var higher = $('#high24').prop("checked");
		
        var players = parseFloat( data[1] ) || 0;
        var maxplayers = parseFloat( data[2] ) || 0;
		
        if ((!higher) || (higher && maxplayers > 24))
        {
            return true;
        }
		
        return false;
    }
);

// < 24 Max Players
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		if(!fullServerTable)
		{
			// Not the correct table. Ignore filter.
			return true;
		}
		
        var lower = $('#low24').prop("checked");
		
        var players = parseFloat( data[1] ) || 0;
        var maxplayers = parseFloat( data[2] ) || 0;
		
        if ((!lower) || (lower && maxplayers <= 24))
        {
            return true;
        }
		
        return false;
    }
);

// Request Status (Admin Panel) 
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		if(settings.nTable.getAttribute('id') != 'requestsadmin')
		{
			// Not the correct table. Ignore filter.
			return true;
		}
		
        var filter = $('#filter-request').val();
		
        var status = parseFloat( data[6] ) || 0;
		
        if ((filter == -1) || (filter == status))
        {
            return true;
        }
		
        return false;
    }
);

function reInitTable(sTable)
{
	if (sTable == '#simpleserverstable')
	{
		// The full browser, use AJAX JSON instead.
		fullServerTable = $(sTable).DataTable({
			"ajax": "/json/" + iGameID + "-fullbrowser.json",
			"lengthMenu": [ 30, 60, 90, 150, 200, 400],
			"order": [[6, 'desc'], [1, 'desc']],
			autoWidth: true,
			responsive: 
			{
				breakpoints: 
				[
					{ name: 'desktop', width: Infinity },
					{ name: 'smalldesktop', width: 1500 },
					{ name: 'tablet',  width: 1024 },
					{ name: 'fablet',  width: 768 },
					{ name: 'phone',   width: 480 }
				],
				details: false,
			},
			"columnDefs":
			[		
				
				// Host Name
				{ className: "desktop smalldesktop tablet fablet phone", "targets": [ 0 ] },					
				
				// Players
				{ className: "desktop smalldesktop", "targets": [ 1 ] },	
				
				// MaxPlayers
				{ className: "desktop smalldesktop", "targets": [ 2 ] },	
				
				// Map
				{ className: "desktop", "targets": [ 3 ] },		
				
				// IP
				{ className: "desktop", "targets": [ 4 ] },	
				
				// Port
				{ className: "desktop", "targets": [ 5 ] },
				
				// Rating
				{ className: "desktop smalldesktop tablet", "targets": [ 6 ] },
				
				// Join button
				{ className: "desktop", "targets": [ 7 ] },				
				
				// Tags
				{ className: "never", "targets": [ 8 ] },
			],
		});
	}
	else if (sTable == '#stocktable')
	{
		// The stock browser, use AJAX JSON instead.
		fullServerTable = $(sTable).DataTable({
			"ajax": "/json/" + iGameID + "-stockbrowser.json",
			"lengthMenu": [ 30, 60, 90, 150, 200, 400],
			"order": [[6, 'desc'], [1, 'desc']],
			autoWidth: true,
			responsive: 
			{
				breakpoints: 
				[
					{ name: 'desktop', width: Infinity },
					{ name: 'smalldesktop', width: 1500 },
					{ name: 'tablet',  width: 1024 },
					{ name: 'fablet',  width: 768 },
					{ name: 'phone',   width: 480 }
				],
				details: false,
			},
			"columnDefs":
			[		
				
				// Host Name
				{ className: "desktop smalldesktop tablet fablet phone", "targets": [ 0 ] },					
				
				// Players
				{ className: "desktop smalldesktop", "targets": [ 1 ] },	
				
				// MaxPlayers
				{ className: "desktop smalldesktop", "targets": [ 2 ] },	
				
				// Map
				{ className: "desktop", "targets": [ 3 ] },		
				
				// IP
				{ className: "desktop", "targets": [ 4 ] },	
				
				// Port
				{ className: "desktop", "targets": [ 5 ] },
				
				// Rating
				{ className: "desktop smalldesktop tablet", "targets": [ 6 ] },
				
				// Join button
				{ className: "desktop", "targets": [ 7 ] },				
				
				// Tags
				{ className: "never", "targets": [ 8 ] },
			],
		});
	}
	else
	{
		fullServerTable = $(sTable).DataTable({
			"lengthMenu": [ 30, 60, 90, 150, 200, 400],
			"order": [[6, 'desc'], [1, 'desc']],
			autoWidth: true,
			responsive: 
			{
				breakpoints: 
				[
					{ name: 'desktop', width: Infinity },
					{ name: 'smalldesktop', width: 1500 },
					{ name: 'tablet',  width: 1024 },
					{ name: 'fablet',  width: 768 },
					{ name: 'phone',   width: 480 }
				],
				details: false,
			},
			"columnDefs":
			[	
				// Host Name
				{ className: "desktop smalldesktop tablet fablet phone", "targets": [ 0 ] },					
				
				// Players
				{ className: "desktop smalldesktop", "targets": [ 1 ] },	
				
				// MaxPlayers
				{ className: "desktop smalldesktop", "targets": [ 2 ] },	
				
				// Map
				{ className: "desktop", "targets": [ 3 ] },		
				
				// IP
				{ className: "desktop", "targets": [ 4 ] },	
				
				// Port
				{ className: "desktop", "targets": [ 5 ] },
				
				// Rating
				{ className: "desktop smalldesktop tablet", "targets": [ 6 ] },
				
				// Join button
				{ className: "desktop", "targets": [ 7 ] },				
				
				// Tags
				{ className: "never", "targets": [ 8 ] },
			],
		});
	}
	
	// Filters.
	$('#hideserversempty, #hideserversfull, #low24, #high24').on("click", function(e)
	{	
		fullServerTable.draw(false);
	});
	
	// Set it to 100% width.
	$(sTable).css('width', '100%');
}

function addServerRow(hostName, players, maxPlayers, map, ip, port, rating, connect, tags, table)
{
	if (fullServerTable)
	{
		fullServerTable.row.add(
			$('<tr>' +
			'<td>' + hostName + '</td>' +
			'<td>' + players + '</td>' +
			'<td>' + maxPlayers + '</td>' +
			'<td>' + map + '</td>' +
			'<td>' + ip + '</td>' +
			'<td>' + port + '</td>' +
			'<td>' + rating + '</td>' +
			'<td>' + connect + '</td>' +
			'<td>' + tags + '</td>' +
			'</tr>')[0]).draw(false);
	}
}

function getFullList(min, max, sTable)
{
	/*
	if (sTable == 'fullbrowser' && serversMethod == 1)
	{
		return;
	}
	*/
	
	if (!fullServerTable)
	{
		reInitTable('#' + sTable);
	}
	
	if (min == 0 && max == 0)
	{	
		// We just want it straight from the list.
		$.get('/scripts/getfulllist.php?min=0&max=0&browser=' + sTable, function (Data)
		{
			if (Data != '' && Data.trim())
			{
				$('#temp').html(Data);
				fullServerTable.columns.adjust().draw(false);
			}
			else
			{
				$('#temp').html('');
			}
		});
	}
	else
	{
		var request = $.get('/scripts/getfulllist.php?min=' + min + '&max=' + max + '&browser=' + sTable, function (Data)
		{
			// Redo the list.
			if (Data != '' && Data.trim())
			{
				$('#temp').html(Data);
				getFullList(min + maxAmount, max + maxAmount, sTable);
				fullServerTable.columns.adjust().draw(false);	
			}
			else
			{
				$('#temp').html('');
			}
		});
	}
}

function submitReview(ip, port)
{
	var rating = $('#rating').val();
	var review = $('#review').val();
	
	$.post('/scripts/submitReview.php', {
		'rating': rating,
		'review': review,
		'ip': ip,
		'port': port,
	}, function (Data)
	{
		if (Data == 1)
		{
			$('#review-block').html('Review submitted!');
		}
		else
		{
			$('#review-block').html('<span class="alert-message">Review not submitted! (' + Data + ')</span>');
		}
	});
}

function claimServer(ip, port)
{
	$.post('/scripts/claimServer.php', {
		'ip': ip,
		'port': port
	}, function (Data)
	{
		if (Data == 1)
		{
			alert ("Successfully claimed server!");
			
			$('#claimItem').html('<span class="glyphicon glyphicon-home custom-unfav-star"></span> Claimed');
		}
		else
		{
			alert("Cannot claim server! Make sure your host-name is set to Browser.TF! Error: " + Data);
		}
	});
}

function changeSMethod()
{
	var sVal = $('#serversmethod').val();
	
	insertParam('serversmethod', sVal);
}

function getServersMethod()
{
	$.get('/scripts/getServerMethod.php', function (Data)
	{
		// Will print 0 or 1.
		if (Data == 1 || Data == 0)
		{
			serversMethod = Data;
		}
	});
}

getServersMethod();

function moveData()
{
	
	var curData = $('#temp').html();

	if (curData != '' && curData.trim())
	{
		$('#table-tbody').html(curData);
		
		$('#temp').html('');
	}
	else
	{
		setTimeout(function()
		{
			moveData();
		}, 1000);
	}
}

// Adds a server.
function addServer()
{
	// Values
	var sIP = $('#ip').val();
	var iPort = $('#port').val();
	var sLocation = $('#location').val();
	var sEMail = $('#email').val();
	var sDescription = $('#description').val();
	
	$.post('/scripts/addserver.php', 
	{
		ip: sIP,
		port: iPort,
		location: sLocation,
		email: sEMail,
		description: sDescription
	}, function (Data)
	{
		if (Data == 1)
		{
			alert('Server successfully added but waiting for approval');
		}
		else
		{
			alert('An error has occurred: ' + Data);
		}
	});
}
// Adds a server to the list.
function addServerToList()
{
	// Values
	var sIP = $('#ip').val();
	var iPort = $('#port').val();
	
	$.post('/scripts/addservertolist.php', 
	{
		ip: sIP,
		port: iPort
	}, function (Data)
	{
		if (Data == 1)
		{
			alert('Server successfully added to the list.');
		}
		else
		{
			alert('An error has occurred: ' + Data);
		}
	});
}

// Reports a server.
function reportServer()
{
	// Values
	var sIP = $('#ip').val();
	var iPort = $('#port').val();
	var sReason = $('#reason').val();
	
	$.post('/scripts/reportserver.php', 
	{
		ip: sIP,
		port: iPort,
		reason: sReason
	}, function (Data)
	{
		if (Data == 1)
		{
			alert('Server successfully reported!');
		}
		else
		{
			alert('An error has occurred: ' + Data);
		}
	});
}

// Add Announcement..
function addAnnouncement()
{
	// Values
	var sTitle = $('#announcement-title').val();
	var sDesc = $('#announcement-description').val();
	var sContent = $('#announcement-content').val();
	
	$.post('/scripts/addannouncement.php', 
	{
		title: sTitle,
		description: sDesc,
		content: sContent
	}, function (Data)
	{
		if (Data == 1)
		{
			alert('Successfully added announcement!');
		}
		else
		{
			alert('An error has occurred: ' + Data);
		}
	});
}

// Opens the Mobile NavBar.
var bOpened = false;
function openMobileNavBar()
{
	if (bOpened)
	{
		// Close NavBar.
		$('#mobile-navbar').animate(
		{
			left: "-50%",
		}, 500, function()
		{
		
		});
		
		bOpened = false;
	}
	else
	{
		// Open NavBar.
		$('#mobile-navbar').animate(
		{
			left: 0
		}, 500, function()
		{
		
		});
		
		bOpened = true;
	}
}

$(window).load(function()
{
	$('#mobile-navbar').on("swipeleft", function(e)
	{
		console.log("Swiped!");
		openMobileNavBar();
	});
});

// Opens secondary menus.
function openSecondaryMenu(id)
{
	// Close all the current menus.
	$('.secondary-menu').each(function()
	{
		$(this).css('display', 'none');
	});
	
	// Open the selected menu.
	$('#secondarylist-' + id).css('display', 'block');
}

// Opens secondary nav slots for mobile.
var bsIsOpened = [];
function openMobileSecondaryList(id)
{
	if (bsIsOpened[id])
	{
		// Is already opened, close it.
		$('#mobile-secondarylist-' + id).css('display', 'none');
		
		// Set arrow to left.
		$('#mobile-arrow-' + id).html('<span class="glyphicon glyphicon-chevron-right mobile-arrow" onClick="openMobileSecondaryList(' + id + '); return false;"></span></span>');
		
		bsIsOpened[id] = false;
	}
	else
	{
		// Open it.
		$('#mobile-secondarylist-' + id).css('display', 'block');
		
		
		// Set arrow to left.
		$('#mobile-arrow-' + id).html('<span class="glyphicon glyphicon-chevron-down mobile-arrow" onClick="openMobileSecondaryList(' + id + '); return false;"></span></span>');
		
		bsIsOpened[id] = true;
	}
}

// When the game is changed.
function gameChange()
{
	// Get Value.
	var val = $('#game').val();
	// Set cookie.
	$.post('/scripts/setGameCookie.php', 
	{
		gameID: val
	}, function (Data)
	{
		// Reload the page.
		location.reload();
	});
}

function getGameID()
{
	if (getCookie('appid') != "")
	{
		iGameID = getCookie('appid');
	}
	else if (getVariable('appid'))
	{
		iGameID = getVariable('appid');
	}
	else
	{
		$.get('/scripts/getGameID.php', function (Data)
		{
			iGameID = Data;
		});
	}
}

function getCookie(cname) 
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
	
    for(var i = 0; i < ca.length; i++) 
	{
        var c = ca[i];
		
        while (c.charAt(0)==' ') 
		{
			c = c.substring(1);
		}
		
        if (c.indexOf(name) == 0) 
		{
			return c.substring(name.length,c.length);
		}
    }
	
    return "";
}

function getVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
	   
       for (var i=0;i<vars.length;i++) 
	   {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
	   
       return(false);
}