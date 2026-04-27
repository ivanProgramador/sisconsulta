<p>Bem ao {{ config('app.name') }}</p>
<p>Foi solicitado o registro do cliente: <strong> {{$company_name}}  </strong> </p>
<p>Para a conclusão do registro, clique no link abaixo por gentileza</p>
<p>
    <a href=" {{ route('conclude.registration',['code' => Crypt::encrypt($code) ]) }}" > Concluir cadastro </a>
</p>

<p>
    Esse link estará disponivel até <strong>{{ now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION')) }}</strong>
</p>

