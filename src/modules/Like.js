import $ from "jquery";
class Like {
  constructor() {
    this.likeBox = $(".like-box");
    this.events();
  }
  events() {
    this.likeBox.on("click", this.likeboxClicked.bind(this));
  }
  likeboxClicked(e) {
    let currentLikeBox = $(e.target).closest(".like-box");
    if (currentLikeBox.attr("data-exists") == "yes") {
      this.dislike(currentLikeBox);
    } else {
      this.like(currentLikeBox);
    }
  }

  like(currentLikeBox) {
    let professorId = currentLikeBox.data("professor");
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", fictionalData.nonce);
      },
      url: fictionalData.root_url + "/wp-json/fictional/v1/manageLike",
      type: "POST",
      data: { professorId: professorId },
      success: (response) => {
        currentLikeBox.attr("data-exists", "yes");
        let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
        likeCount++;
        currentLikeBox.find(".like-count").html(likeCount);
        currentLikeBox.attr("data-like", response);
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      },
    });
  }
  dislike(currentLikeBox) {
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-Nonce", fictionalData.nonce);
      },
      url: fictionalData.root_url + "/wp-json/fictional/v1/manageLike",
      type: "DELETE",
      data: {
        like: currentLikeBox.attr("data-like"),
      },
      success: (response) => {
        currentLikeBox.attr("data-exists", "no");
        let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
        likeCount--;
        currentLikeBox.find(".like-count").html(likeCount);
        currentLikeBox.attr("data-like", "");
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      },
    });
  }
}
export default Like;
