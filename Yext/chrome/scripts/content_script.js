$baseURL = "https://local.puneet.com/Plugins/Yext/";

function sendLeads(profile, listing){
    jQuery.ajax({
        url: $baseURL+'sendData.php',
        method: 'post',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({profile: profile, listing: listing}),
        success:function (response) {
            console.log(response);
        },
        error: function(err){
            console.log(err);
        }
    })
}


function parseListings(){
    var listing = [];
    var profile = {};
    var details= jQuery('div.business-scan-header-address').html().replace(/\s\s+/g, ' ').split('<br>');

    profile.name = details[0].trim();

    profile.street = details[1].trim();

    var city_state= details[2].split(',');
    profile.city = city_state[0].trim();

    profile.state = city_state[1].trim();

    profile.phone = jQuery('div.business-scan-header-address span#phnspan').text().trim();

    profile.Optimization = jQuery('.results-badge-square-text-wrapper').text().replace(/\s\s+/g, ' ').trim();

    profile.Optimization_rate = jQuery('.js-percent-optimized:eq(1)').text().trim();
    
    
    jQuery('tbody.js-template-body > tr').each(function(){
        var list = {name: null, address: null, phone: null};
        var $_this = jQuery(this);
        var logo = $_this.find('div.publisher-details-icon img');
        list.logo = logo.length ? logo.attr('src') : null;

        var profileLink = $_this.find('div.publisher-details-info a');
        list.profileLink = profileLink.length ? profileLink.attr('href') : null;

        var listingOn = $_this.find('div.publisher-details-info');
        list.listingOn = listingOn.length ? listingOn.text().trim().split(/\s\s+/g)[0] : null;

        if($_this.find('td.on-desktop.on-tablet').length === 4){
            list.name = $_this.find('td.on-desktop.on-tablet:eq(0)').text().trim();
            list.address = $_this.find('td.on-desktop.on-tablet:eq(1)').text().trim();
            list.phone = $_this.find('td.on-desktop.on-tablet:eq(2)').text().trim();
        }

        var status = $_this.find('div.status-badge');
        list.status = status.length ? status.text().replace('!', '').replace('X', '').trim() : null;
        
        listing.push(list);
    })
    sendLeads(profile, listing);
}

jQuery(document).ready(function(){
    // if(window.location.pathname === '/'){
    //     window.location.replace('https://www.yext.com/pl/powerlistings/scan.html');
    // }else{
        setTimeout(parseListings, 10000);
    // }
})
