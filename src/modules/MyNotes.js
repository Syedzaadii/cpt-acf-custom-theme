import $ from "jquery";

class MyNotes {
  //
  constructor() {
    this.submiteBtn = $(".submit-note");
    this.events();
  }

  //events
  events() {
    $("#mynotes").on("click", ".delete-note", this.deleteNote.bind(this));
    $("#mynotes").on("click", ".edit-note", this.editNote.bind(this));
    $("#mynotes").on("click", ".update-note", this.updateNote.bind(this));
    this.submiteBtn.on("click", this.createNote.bind(this));
  }

  deleteNote(e) {
    let thisNote = $(e.target).parents("li");
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-NONCE", fictionalData.nonce);
      },
      url:
        fictionalData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"),
      type: "DELETE",
      success: (response) => {
        thisNote.slideUp();
        console.log(response);
        if (response.userNoteCount < 5) {
          $(".note-limit-message").removeClass("active");
        }
      },
      error: (response) => {
        console.log(response);
      },
    });
  }
  editNote(e) {
    let thisNote = $(e.target).parents("li");
    if (thisNote.data("state") == "edit") {
      this.makeNoteReadonly(thisNote);
    } else {
      this.makeNoteEditable(thisNote);
    }
  }

  makeNoteEditable(thisNote) {
    thisNote
      .find(".edit-note")
      .html('<i class="fa fa-times" aria-hidden="true"> Cancel</i>');
    thisNote
      .find(".note-title-field, .note-body-field")
      .removeAttr("readonly")
      .addClass("note-active-field");
    thisNote.find(".update-note").addClass("update-note--visible");
    thisNote.data("state", "edit");
  }
  makeNoteReadonly(thisNote) {
    thisNote
      .find(".edit-note")
      .html('<i class="fa fa-pencil" aria-hidden="true"> Edit</i>');
    thisNote
      .find(".note-title-field, .note-body-field")
      .attr("readonly", "readonly")
      .removeClass("note-active-field");
    thisNote.find(".update-note").removeClass("update-note--visible");
    thisNote.data("state", "update");
  }

  updateNote(e) {
    let thisNote = $(e.target).parents("li");
    let ourUpdateNote = {
      title: thisNote.find(".note-title-field").val(),
      content: thisNote.find(".note-body-field").val(),
    };
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-NONCE", fictionalData.nonce);
      },
      url:
        fictionalData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"),
      type: "POST",
      data: ourUpdateNote,
      success: (response) => {
        this.makeNoteReadonly(thisNote);
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      },
    });
  }

  createNote() {
    let ourNoteData = {
      title: $(".new-note-title").val(),
      content: $(".new-note-body").val(),
      status: "publish",
    };
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader("X-WP-NONCE", fictionalData.nonce);
      },
      url: fictionalData.root_url + "/wp-json/wp/v2/note/",
      type: "POST",
      data: ourNoteData,
      success: (response) => {
        $(".new-note-title, .new-note-body").val("");
        $(
          `
            <li data-id="${response.id}">
                <input value="${response.title.raw}" class="note-title-field" readonly>
                <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"> Edit</i></span>
                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"> Delete</i></span>
                <textarea readonly
                    class="note-body-field">${response.content.raw}</textarea> 
                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true">
                        Save</i></span>
            </li>
           `
        )
          .prependTo("#mynotes")
          .hide()
          .slideDown();
        console.log(response);
      },
      error: (response) => {
        if (response.responseText == "You have reached note limit") {
          $(".note-limit-message").addClass("active");
        }
        console.log(response);
      },
    });
  }
}
export default MyNotes;
