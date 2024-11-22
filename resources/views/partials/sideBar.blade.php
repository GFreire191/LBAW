<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">


<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">


<link rel="stylesheet" href="{{ asset('css/sideBar.css') }}">


<div id="sidebar">

    <button id="menu-button">
        <i class="fas fa-bars"></i>
    </button>


    <div id="sideBarContent">
        @if($page == 'home')
            <h2 class="sideBarTitle"> Tags: </h2>
            @if(count($tags) <= 6)
                @foreach($tags as $tag)
                    <li><a href="#" data-tag-id="{{$tag->id}}" class ="tag-bar-btn">{{$tag->name}}</a></li>
                @endforeach
            @else
                @for($i = 0; $i < 6; $i++)
                    <li><a href="#" data-tag-id="{{$tags[$i]->id}}" class ="tag-bar-btn">{{$tags[$i]->name}}</a></li>
                @endfor
                <li id="moreTagButton"><span class ="tag-bar-btn" id="moreTagButton">View More</span></li>

                <div id="additional-tags">
                    @for($i = count($tags) - 1; $i < count($tags); $i++)
                        <li><a href="#" data-tag-id="{{$tags[$i]->id}}" class ="tag-bar-btn">{{$tags[$i]->name}}</a></li>
                    @endfor
                </div>
            @endif

        @elseif($page == 'admin')
            <h2 class="sideBarTitle"> Manage: </h2>
            <li class="admin-side-btn"><a href="{{ route('admin.reports') }}">Reports</a></li>
            <li class="admin-side-btn" id="adminTagButton"><a href="{{ route('admin.tags') }}">Tags</a></li>
            
        @endif
    
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        var menuButton = document.getElementById('menu-button');
        var sidebar = document.getElementById('sidebar');
        var icon = menuButton.getElementsByTagName('i')[0];

        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            if (sidebar.classList.contains('open')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars'; 
            }
        });

        let moreTagButton = document.getElementById('moreTagButton');
        let additionalTags = document.getElementById('additional-tags');
        if(moreTagButton){
            moreTagButton.addEventListener('click', function() {
                if (additionalTags.style.display == 'block') {
                    let moreTagButton = document.getElementById('moreTagButton');
                    additionalTags.style.display = 'none';
                    moreTagButton.textContent = 'View More';
                    return;    
                }
            
                additionalTags.style.display = 'block';
                moreTagButton.textContent = 'View Less';
        
            });
        }

        let adminTagButton = document.getElementById('adminTagButton');
        if(adminTagButton){
            adminTagButton.addEventListener('click', function() {
                event.preventDefault();
                let url = adminTagButton.getElementsByTagName('a')[0].href;
                sendAjaxRequest('get', url, null, function() {
                    let adminContentContainer = document.getElementsByClassName('admin-content-container')[0];
                    adminContentContainer.innerHTML = this.responseText;
                    let adminTagButton = document.getElementById('adminTagButton');
                    adminTagButton.classList.add('active');
                    let adminReportButton = document.getElementsByClassName('admin-side-btn')[0];
                    adminReportButton.classList.remove('active');
                });
            });
        }
    });
</script>


