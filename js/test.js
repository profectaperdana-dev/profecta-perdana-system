// get data title anime from api
$.ajax({
    url: 'https://api.jikan.moe/v3/search/anime?q=one%20piece',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        console.log(data);
    }
});
