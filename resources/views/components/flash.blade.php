@if(session('status'))
    <div class="alert alert--success" role="status">
        <button class="alert-dismiss" type="button" data-dismiss-alert aria-label="Fermer">×</button>
        {{ session('status') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert--danger" role="alert">
        <button class="alert-dismiss" type="button" data-dismiss-alert aria-label="Fermer">×</button>
        {{ session('error') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert--danger" role="alert">
        <button class="alert-dismiss" type="button" data-dismiss-alert aria-label="Fermer">×</button>
        <strong>Veuillez corriger les erreurs ci-dessous.</strong>
    </div>
@endif
