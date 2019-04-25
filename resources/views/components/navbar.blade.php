<nav class="main-navbar">
    <ul>
        <li class="people" id="people-link">People</li></a>
        <li class="video">Video</li>
        <li class="discussion active">Discussion</li>
    </ul>
</nav>

@push('extra-js')
<script type="text/javascript">
    $('body').on('click', '.people', showQuipsPics);

    $('body').on('click', '.video', function(){
        alternateActive(this);
    });
    $('body').on('click', '.discussion', function(){
        alternateActive(this);

        if (this.classList.contains('active'))
          $.ajax({
              url: url,
              complete: function (data) {
                loading = false;
                if (data.posts == null) {
                  loading = true;
                  container.append('No more posts');
                }
              }
              ,
              success: function(data) {
                container.querySelector('.loader').remove();
                $(container).append(data.posts);
              }
              ,
              error: function(xhr, status, error) {
                alert(xhr.responseText);
              }
          });
    });

    $('body').on('click', '.pictures', function(){
        let people = this.parentNode.parentNode;
        let quipsHasActive = $(this).siblings()[0].classList.contains('active');
        let pictureHasActive = this.classList.contains('active');

        // If quips and pics are inactive show people again
        if (pictureHasActive && !quipsHasActive) {
            removeQuipsPics = this.parentNode.parentNode;
            while (removeQuipsPics.firstChild)
                removeQuipsPics.removeChild(removeQuipsPics.firstChild);
            people.innerHTML = 'People';
            $('body').on('click', '.people', showQuipsPics);
        }
        else if (pictureHasActive)
            this.classList.remove('active');
        else 
            this.classList.add('active')
    });

    $('body').on('click', '.quips', function(){
        let people = this.parentNode.parentNode;
        let pictureHasActive = $(this).siblings()[0].classList.contains('active');
        let quipsHasActive = this.classList.contains('active');

        // If quips and pics are inactive show people again
        if (quipsHasActive && !pictureHasActive) {
            removeQuipsPics = this.parentNode.parentNode;
            while (removeQuipsPics.firstChild)
                removeQuipsPics.removeChild(removeQuipsPics.firstChild);
            people.innerHTML = 'People';
            $('body').on('click', '.people', showQuipsPics);
        }
        else if (quipsHasActive)
            this.classList.remove('active');
        else 
            this.classList.add('active')
    });

    function alternateActive (element) {
        if (element.classList.contains('active'))
            element.classList.remove('active');
        else 
            element.classList.add('active')
    }

    function showQuipsPics (){
        let peopleLink = document.getElementById('people-link');
        peopleLink.removeChild(peopleLink.firstChild);

        let quipPics = createQuipsPics();
        peopleLink.appendChild(quipPics);
        $('body').off('click', '.people', showQuipsPics);
    }

    function createQuipsPics () {
        let list = document.createElement('ul');
        let quips = document.createElement('li');
        quips.className = 'quips active';
        quips.innerHTML = 'Quips';
        let pics = document.createElement('li');
        pics.className = 'pictures active';
        pics.innerHTML = 'Pictures';
        list.appendChild(quips);
        list.appendChild(pics);
        return list;
    }
    function removeAll (myNode){
        while (myNode.firstChild) {
            myNode.removeChild(myNode.firstChild);
        }
    }

</script>
@endpush

