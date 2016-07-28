/**
*	Name: custom_serverbrowser.js
*	Author: Christian Deacon (Roy)
*	Note: Creates the web-sided server browser for TF2.
*	Date: 7-13-16
**/

/* Global variables. */
var g_dtServerBrowser;

/* Configurable variables. */
g_iMaxServers = 500;

/**
*	Name: isGoodServer();
*	Description: Checks if the server has valid information.
*	@return true for "good", false for "bad".
**/
function isGoodServer(data)
{
	/* Checks. */
	if (!data)
		return false;
		
	if (data.max_players < 1)
		return false;
		
	if (!data.map || data.map.trim() == '')
		return false;
	
	return true;
}

/**
 * Decodes utf-8 encoded string back into multi-byte Unicode characters.
 *
 * Can be achieved JavaScript by decodeURIComponent(escape(str)),
 * but this approach may be useful in other languages.
 *
 * @param {string} strUtf UTF-8 string to be decoded back to Unicode.
 * @returns {string} Decoded string.
 */
function Utf8Decode(strUtf) {
    // note: decode 3-byte chars first as decoded 2-byte strings could appear to be 3-byte char!
    var strUni = strUtf.replace(
        /[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g,  // 3-byte chars
        function(c) {  // (note parentheses for precedence)
            var cc = ((c.charCodeAt(0)&0x0f)<<12) | ((c.charCodeAt(1)&0x3f)<<6) | ( c.charCodeAt(2)&0x3f);
            return String.fromCharCode(cc); }
    );
    strUni = strUni.replace(
        /[\u00c0-\u00df][\u0080-\u00bf]/g,                 // 2-byte chars
        function(c) {  // (note parentheses for precedence)
            var cc = (c.charCodeAt(0)&0x1f)<<6 | c.charCodeAt(1)&0x3f;
            return String.fromCharCode(cc); }
    );
    return strUni;
}

$(document).ready(function()
{	
	/* Start the DataTable. */
	g_dtServerBrowser = $('#serverbrowser').DataTable({
		processing: true,
		ajax: "serversToDT.json",
		autoWidth: true,
		scrollY: 800,
		scrollX: true,
		deferRender: true,
		scroller: true,
		order: [[3, "desc"], [4, "desc"]],
		lengthMenu: [[50, 100, 500, 1000, 2000, -1], [50, 100, 500, 1000, 2000, "All"]],
		columns: [
			/* Server Name. */
			{
				title: "Hostname",
				render: function ( data, type, full, meta ) { return data == null ? "" : Utf8Decode(data); }
			},
			
			/* Server IP. */
			{
				title: "IP",
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},			
			
			/* Server Port. */
			{
				title: "Port",
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},			
			
			/* Server Players. */
			{
				title: "Players",
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},			
			
			/* Server MaxPlayers. */
			{
				title: "MaxPlayers",
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},			
			
			/* Server Map. */
			{
				title: "Map",
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},			
			
			/* Options */
			{
				title: "Options",
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},
			
			/* Tags (Hidden) */
			{
				title: "Tags",
				visible: false,
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},			
			
			/* Region (Hidden) */
			{
				title: "Region",
				visible: false,
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			},			
			
			/* Secure (Hidden) */
			{
				title: "Secure",
				visible: false,
				render: function ( data, type, full, meta ) { return data == null ? "" : data; }
			}			
		],
	});
	
	/* Apply filters. */
	$('#filter-hideempty, #filter-hidefull, #filter-high24, #filter-low24').on('click', function (e) {
		g_dtServerBrowser.draw(false);
	});
	
	$('#filter-region, #filter-secure').change(function () {
		g_dtServerBrowser.draw(false);
	});
	
	$('#filter-map').keyup(function() {
		g_dtServerBrowser.draw(false);
	});
});

/* Filters. */

/* Hide Empty. */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		var empty = $('#filter-hideempty').prop("checked");
		var players = data[3];
		
		if (empty && players < 1)
		{
			return false;
		}
		
		return true;
    }
);

/* Hide Full. */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		var full = $('#filter-hidefull').prop("checked");
		var players = data[3];
		var maxplayers = data[4];
		
		if (full && players >= maxplayers)
		{
			return false;
		}
		
		return true;
    }
);

/* > 24 players. */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		var high = $('#filter-high24').prop("checked");
		var maxplayers = data[4];
		
		if (high && maxplayers <= 24)
		{
			return false;
		}
		
		return true;
    }
);

/* 24 >= players. */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		var low = $('#filter-low24').prop("checked");
		var maxplayers = data[4];
		
		if (low && maxplayers > 24)
		{
			return false;
		}
		
		return true;
    }
);

/* Region. */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		var region = $('#filter-region').val();
		var serverRegion = data[8];
		
		if (region == -1)
		{
			return true;
		}

		if (region != serverRegion)
		{
			return false;
		}
		
		return true;
    }
);

/* Secure (Anti-Cheat). */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		var secure = $('#filter-secure').val();
		var serverSecure = data[9];
		
		if (secure == -1)
		{
			return true;
		}

		if (secure != serverSecure)
		{
			return false;
		}
		
		return true;
    }
);

/* Map. */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) 
	{
		var map = $('#filter-map').val();
		var serverMap = data[5];
		
		if (map.trim() == '')
		{
			return true;
		}

		if (!serverMap.includes(map.trim()))
		{
			return false;
		}
		
		return true;
    }
);
