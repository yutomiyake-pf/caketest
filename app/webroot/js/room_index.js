/**
 * room_index専用js
 * 
 */

//room_indexの写真投稿数を取得(ajax)
let imageCnt;
$.ajax({
    type: "GET",
    dataType: "json",
    url: "/Debug/Ajax/getIndexImageCntByRoomId/" + roomId,
    cache: false,
    timeout: 10000
}).done(function(json) {
    imageCnt = $.parseJSON(json);

    $("#image-cnt").append('<p>'+imageCnt+'</p>');
});