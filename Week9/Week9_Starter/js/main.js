console.log("main js loaded");

// get needed elements
let theform = document.querySelector("form.comment-form");
let thecomment = document.querySelector(".comment-form textarea");
let hiddeninput = document.querySelector(".comment-form input");
let commentsdiv = document.querySelector(".comments");
let commentcard = document.querySelectorAll(".card");

// add event listener, prevent default submission and get
//textarea value

theform.addEventListener("submit", function(event) {
  event.preventDefault();
  let querystring = hiddeninput.value;
  let postid = querystring.split("="); id=7 [id, 7]
  let comment = thecomment.value;
  commentAjax(comment, postid[1]);
  theform.reset();
})



// ajax request

function commentAjax(comment, postid) {

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "func/ajaxmanager.php", true);
  // to use the post method we must set the request headers
  // depending on the form data being sent
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function() {
    if(this.status == 200) {
      console.log(JSON.parse(this.responseText));
      outputNewComment(JSON.parse(this.responseText));
    }
  }
  xhr.send("comment="+comment+"&post_id="+postid);
}

function replyAjax(data_comment_id, data_comment_user_id, comment_text, wrapperdiv) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "func/ajaxmanager.php", true);
  // to use the post method we must set the request headers
  // depending on the form data being sent
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function() {
    if(this.status == 200) {
      let result = JSON.parse(this.responseText);
      console.log(result);
      outputNewReply(result, wrapperdiv);
    }
  }
  xhr.send("reply="+comment_text+"&data_comment_id="+data_comment_id+"&data_comment_user_id="+data_comment_user_id);
}


function deleteCommentAjax(comment_id, parent_card) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "func/ajaxmanager.php", true);
  // to use the post method we must set the request headers
  // depending on the form data being sent
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function() {
    if(this.status == 200) {
      if(this.responseText == true) {
        parent_card.classList.add("shrinkStart");
        setTimeout(function(){
          parent_card.classList.add("shrinkFinish");
        },100);
        setTimeout(function(){
          parent_card.remove();
          notification("Comment successfully removed!", "success", "fas fa-check-circle");
        },400);
      } else {
        notification("Could not remove this comment!", "danger", "fas fa-times");
      }

    }
  }
  xhr.send("delete-comment=true&comment_id="+comment_id);
}


// General function

function outputNewReply(output, wrapperdiv) {
  let parentdiv = document.createElement('div');
  parentdiv.classList = "col-md-8 offset-md-1 mt-2 mb-2 comment reply";
  let theoutput = `<div class="card"><div class="card-header">${output.user_name} replying to ${output.reply_to_user}| ${output.date_created}</div>
  <div class="card-body"><p class="card-text">${output.comment_text}</p>
    <button class='btn mr-1 ml-1 float-right btn-sm btn-outline-secondary reply-comment' data-comment-id='${output.comment_id}' data-comment-user-id='${output.comment_user}'>reply</button>
   <button class='btn float-right btn-sm btn-outline-danger delete-post' data-comment-id='${output.comment_id}'>X</button>
  </div></div>`;
  parentdiv.innerHTML = theoutput;
  wrapperdiv.append(parentdiv);
  setTimeout(function(){
    notification("New comment added", "success", "far fa-plus-square");
  },300);
}


function outputNewComment(output) {
  let wrapperdiv = document.createElement('div');
  wrapperdiv.classList = "col-md-8 mt-2 mb-2 shrink comment-wrapper";
  let theoutput = `<div class="card"><div class="card-header">${output.user_name} | ${output.date_created} <button class='btn float-right btn-sm btn-outline-danger delete-post' data-comment-id='${output.comment_id}'>X</button></div>
  <div class="card-body"><p class="card-text">${output.comment_text}</p>
  </div></div>`;
  wrapperdiv.innerHTML = theoutput;
  console.log(wrapperdiv);
  commentsdiv.prepend(wrapperdiv);
  setTimeout(function(){
    wrapperdiv.classList.remove("shrink");
  },10);

  setTimeout(function(){
    wrapperdiv.classList.add("grow");
  },20);
  setTimeout(function(){
    notification("New comment added", "success", "far fa-plus-square");
  },300);


}


  commentsdiv.addEventListener("click", function(e) {
    e.preventDefault();
    console.log("click");
    if(e.target.classList.contains("delete-post")){
      let comment_target = e.target;
      let comment_id = e.target.getAttribute("data-comment-id");
      console.log("delete:" + comment_id);
      let parent_card = e.closest(".card");
      deleteCommentAjax(comment_id, parent_card);
    } else if (e.target.classList.contains("reply-comment")) {
      createReplyForm(e.target);
    } else if (e.target.classList.contains("comment-reply")) {
      createReply(e.target);
    }

    console.log(e);
  });

function createReply(el) {
  // we need three vals for the AJAX req: 1) comment text
  // 2) parent_comment_id, 3) parent comment user
  // also remove active class from comment-wrapper and remove form node
    let replyform = el.closest("form");
    let comment_text = replyform.querySelector("textarea").value;
    let data_comment_id = replyform.getAttribute("data-comment-id");
    let data_comment_user_id = replyform.getAttribute("data-comment-user-id");
    let wrapperdiv = el.closest(".comment-wrapper");
    wrapperdiv.classList.remove("active");
    replyform.remove();
    replyAjax(data_comment_id, data_comment_user_id, comment_text, wrapperdiv);
}


function createReplyForm(el) {
  let data_comment_id = el.getAttribute("data-comment-id");
  let data_comment_user_id = el.getAttribute("data-comment-user-id");
  let wrapperdiv = el.closest(".comment-wrapper");
  wrapperdiv.classList.add("active");
  let formclone = theform.cloneNode(true);
  formclone.setAttribute("data-comment-id",data_comment_id);
  formclone.setAttribute("data-comment-user-id",data_comment_user_id);
  formclone.classList.add("mt-2");
  console.log(el);
  wrapperdiv.append(formclone);

}


function notification(msg, msgClass, icon = "") {
  let overlay = document.createElement("div");
  overlay.classList = "overlay";
  let notification = `<div class='alert alert-${msgClass}'><i class="${icon}"></i> ${msg}</div>`;
  overlay.innerHTML = notification;
  let body = document.querySelector("body");
  body.append(overlay);
  setTimeout(function() {
    overlay.style.opacity = "1";
  }, 10);
  setTimeout(function() {
    overlay.style.opacity = "0";
  }, 1500);
  setTimeout(function() {
    overlay.remove();
  }, 1800);
}
