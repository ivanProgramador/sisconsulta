<div class="flex p-4 px-8 bg-cyan-700">

    <div class="flex gap-4 text-lg">
            {{-- 
            No codigo abaixo a diretiva "can" serve parar testar as Gates então quando eu aciono ela 
            e coloco como parametro a gate 'sys-admin' ela executa a funcção de teste que eu coloquei
            dentro da Gate que vai testar se o role do usuarios logado é 'sys-admin' se for ela retorna true 
            os menus envolvidos vão aparecer na tela do usuário, caos contrario ela retona false e esses menus 
            vão ser apagados da tela, não é uma questão de so ocultar é uma questão de impossibilitar que mesmo 
            acessando a ferramenta de desenvolvedor no lado cliente o usuario consiga ter acesso a esses componentes.
            
            é assim que as gates funcionan          
            --}}

         @can('sys-admin')

             <a href="{{ route('admin.home') }}" class="btn-white"><i class="fa-solid fa-house me-2"></i>Clientes</a>
             <a href="#" class="btn-white"><i class="fa-solid fa-chart-column me-2"></i>Estatisticas</a>

         @endcan

         {{--
           No caso abaixo eu estou testando 2 tipos de gates 

           1- client-admin
           2- client-user
           
           Estou fazendo isso porque se trata de um main menu onde 2 tipos de usuarios podem ter autorização de acesso
           então eu uso a diretiva 'canany' isso possibilita filtrar quam esta acessando e qua tiposs de recurso cada um vai 
           ter acesso, porque é obvio que existem coisas que um administrador pode ver e um usuario comum não pode, mesmo que
           estejam usando o mesmo componente de menu
         

         --}}

        @canany(['client-admin','client-user'])

            <a href="{{ route('home') }}" class="btn-white"><i class="fa-solid fa-house me-2"></i>Gestão de filas</a>
            <a href="{{ route('bundles.home') }}" class="btn-white"><i class="fa-solid fa-table-list me-2"></i>Gestão de grupos de fila</a>
            <a href="{{ route('dispenser') }}" target="_blank" class="btn-white"><i class="fa-regular fa-copy me-2"></i>Dispensador</a>
            <a href="{{ route('queues.display') }}" target="_blank" class="btn-white"><i class="fa-solid fa-tv me-2"></i>Apresentador</a>
            <a href="{{ route('caller.home') }}" class="btn-white"><i class="fa-solid fa-share-from-square me-2"></i>Chamador</a>

            @can()
              
            @endcan

        @endcanany

        

      
    </div>

</div>
