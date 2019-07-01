<!DOCTYPE html>
<html>
<head>
  <title>Create Event</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">
</head>
<body>
  <form method="POST" action="/events" class="container" style="padding-top: 40px;">
    @csrf
    <h1 class="heading is-1">Create an Event</h1>

    <div class="field">
      <label class="label" for="title">Title</label>

      <div class="control">
        <input type="text" name="title" class="input" placeholder="Title">
      </div>
    </div>

    <div class="field">
      <label class="label" for="description">Description</label>

      <div class="control">
        <textarea name="description" class="textarea"></textarea>
      </div>
    </div>
    
    <div class="field">
      <div class="control">
        <button type="submit" class="button fa-link">Create</button>
      </div>
    </div>
  </form>
</body>
</html>