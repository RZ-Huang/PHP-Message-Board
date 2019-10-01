$(document).ready(() => {
  // 事件監聽：主留言的刪除
  $('.comments').on('click', '.delete-comment', (e) => {
    const id = $(e.target).attr('data-id');

    if (id === '') return alert('刪除失敗，請重新操作');

    if (!window.confirm('確定刪除留言？')) return null;

    $.ajax({
      method: 'POST',
      url: './handle_delete.php',
      async: true,
      data: {
        id,
      },

    }).done((response) => {
      const msg = JSON.parse(response);
      alert(msg.message);
      $(e.target).parent('.main-comment').parent('.comment').hide(200);
    }).fail(() => {
      alert('delete failed');
    });

    return null;
  });

  // 事件監聽：子留言的刪除
  $('.comments').on('click', '.delete-sub-comment', (e) => {
    const id = $(e.target).attr('data-id');

    if (id === '') return alert('刪除失敗，請重新操作');

    if (!window.confirm('確定刪除留言？')) return true;


    $.ajax({
      method: 'POST',
      url: './handle_sub_delete.php',
      async: true,
      data: {
        id,
      },

    }).done((response) => {
      const msg = JSON.parse(response);
      alert(msg.message);
      $(e.target).parent('.sub-comment').hide(200);
    }).fail(() => {
      alert('delete failed');
    });

    return null;
  });

  // 事件監聽：主留言的新增
  $('#main-submit').on('click', (e) => {
    e.preventDefault();
    const content = $('.main-text').val();

    if (content === '') return alert('請輸入留言內容');

    if (!window.confirm('確定新增留言？')) return true;

    $.ajax({
      method: 'POST',
      url: './handle_add.php',
      async: true,
      data: {
        content,
      },

    }).done((response) => {
      const msg = JSON.parse(response);
      let htmlContent = `
      <div class="comment">  
        <div class="main-comment">    
          <h1>${msg.nickname}</h1>    
          <h2>${msg.content}</h2>    
          <p class="createdTime-comment">${msg.createdTime}</p>  
        `;

      if (msg.username === msg.sessionUsername) {
        htmlContent
          += `
          <a href='./edit.php?id=${msg.id}' class='edit-comment'>編輯</a>
          <button class='delete-comment' data-id='${msg.id}'>刪除</button>
          `;
      }

      htmlContent
        += `
      
        </div>  
        <div class="sub-comments">
          <h1>回覆留言</h1>
          <form method="POST" class='sub-form'>  
            <div class="text">    
              <textarea name="text" rows="10" cols="50" class='sub-text'></textarea>   
            </div>   
            <button type='submit' class='btn btn-outline-primary btn-lg btn-block' id='sub-submit'>提交留言</button>
            <input type="hidden" name="comment-id" value="${msg.id}">
          </form>
        </div>
      </div>
      `;
      $('.comments').prepend(htmlContent);
      $('.main-form')[0].reset();
      alert(msg.message);
    }).fail(() => {
      $('.main-form')[0].reset();
      alert('add failed');
    });

    return null;
  });

  // 事件監聽：子留言的新增
  $('.comments').on('click', '#sub-submit', (e) => {
    e.preventDefault();
    const selected = $(e.target);
    const content = selected.parent().find('.sub-text').val();
    const commentID = selected.siblings(':hidden').attr('value');
    const subFormLength = $('.sub-form').length;
    if (content === '') return alert('請輸入留言內容');

    if (!window.confirm('確定新增留言？')) return true;
    $.ajax({
      method: 'POST',
      url: './handle_sub_add.php',
      async: true,
      data: {
        content,
        commentID,
      },
    }).done((response) => {
      const msg = JSON.parse(response);

      let htmlContent = '';
      if (msg.username === msg.mainCommentUsername) {
        htmlContent = `
        <div class="sub-comment pop">  
      `;
      } else {
        htmlContent = `
        <div class="sub-comment">
        `;
      }

      htmlContent
        += `
          <h1>${msg.nickname}</h1>  
          <h2>${msg.content}</h2>  
          <p>${msg.createdTime}</p>  
        `;
      if (msg.username === msg.sessionUsername) {
        htmlContent
          += `
          <a href="./sub_edit.php?id=${msg.subCommentId}" class="edit-comment">編輯</a>  
          <button class="delete-sub-comment" data-id="${msg.subCommentId}">刪除</button>
         `;
      }
      if (msg.username === msg.mainCommentUsername) {
        htmlContent
          += `
        </div>
          `;
      }

      selected.parent().parent('.sub-comments').prepend(htmlContent);
      for (let i = 0; i < subFormLength; i += 1) {
        $('.sub-form')[i].reset();
      }
    }).fail(() => {
      for (let i = 0; i < subFormLength; i += 1) {
        $('.sub-form')[i].reset();
      }
      alert('add failed');
    });

    return null;
  });
});
