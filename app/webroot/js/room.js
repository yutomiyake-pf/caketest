/** 
 * 画像投稿ルームの共通js
 * 
 */

//選択された画像の名前を出す
window.addEventListener('DOMContentLoaded', function() {
// 指定されると動くメッソド
document.querySelector("#image-file").addEventListener('change', function(e) {
// ブラウザーがFile APIを利用できるか確認
if (window.File) {
// 指定したファイルの情報を取得
var input = document.querySelector('#image-file').files[0];
var message = "を選択中";
// 最後に、反映
document.querySelector('#output').innerHTML = input.name + message;
// document.querySelector('#type').innerHTML = input.type;
// document.querySelector('#size').innerHTML = input.size / 1024;
// document.querySelector('#daytime').innerHTML = input.lastModifiedDate　;
}
}, true);
});


//写真投稿formのモーダル
const open = document.getElementById('open');
const close = document.getElementById('close');
const modal = document.getElementById('modal');
const mask = document.getElementById('mask');

open.addEventListener('click',()=>{
    modal.classList.remove('hidden');
    mask.classList.remove('hidden');
});
    
close.addEventListener('click',()=>{
    modal.classList.add('hidden');
    mask.classList.add('hidden');
});

mask.addEventListener('click',()=>{
    close.click();
});

//ルームの詳細モーダル
const infoOpen = document.getElementById('info-open');
const infoClose = document.getElementById('info-close');
const infoModal = document.getElementById('info-modal');
const infoMask = document.getElementById('info-mask');

infoOpen.addEventListener('click',()=>{
    infoModal.classList.remove('info-hidden');
    infoMask.classList.remove('info-hidden');
});

infoClose.addEventListener('click',()=>{
    infoModal.classList.add('info-hidden');
    infoMask.classList.add('info-hidden');
});

infoMask.addEventListener('click',()=>{
    close.click();
});

//ルーム詳細更新ボタン
// const infoReload = document.getElementById('info-reload');

// infoReload.addEventListener('click',()=>{
//     location.reload();
//     infoModal.classList.remove('info-hidden');
//     infoMask.classList.remove('info-hidden');
// })


/** 
 * jquery
 * 
 * 
 */

//写真削除(ajax)
$(function() {
    $('a.delete').click(function(e) {
        if (confirm($(this).data('created')+'にあなたが投稿した画像です。削除しますか？')) {
            $.post('/Debug/Rooms/deleteImage/'+$(this).data('post-id'),{},function(res) {
                $('#link-'+res.id).fadeOut();
                $('#my-image-'+res.id).fadeOut();
            },"json");
        }
        return false;
    });
});
