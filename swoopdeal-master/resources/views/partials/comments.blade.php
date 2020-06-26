<form class="comment-form" id="respond">
  <h3>Add a review</h3>
  <p class="comment-notes"><span id="email-notes">Your email address will not be published.</span> Required fields are marked<span class="required">*</span></p>
  <div class="row">
    <div class="form-group comment-form-author col-sm-6">
      <label for="author">Name<span class="required">*</span></label>
      <input class="form-control" id="author" name="author" type="text" required value="" placeholder="Enter your name">
    </div>
    <div class="form-group comment-form-email col-sm-6">
      <label for="email">Email<span class="required">*</span></label>
      <input class="form-control" id="email" name="email" type="email" required value="" placeholder="Enter your email">
    </div>
  </div>
  <div class="form-group comment-form-comment">
    <label for="comment">Comment<span class="required">*</span></label>
    <textarea class="form-control" id="comment" name="comment" required placeholder="Enter your message"></textarea>
  </div>
  <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Submit</button>
</form>