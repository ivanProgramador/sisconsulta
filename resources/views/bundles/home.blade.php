<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">
   <div class="main-card overflow-auto">
       <div class="flex justify-between items-center">
          <p class="title-2">Coleção de filas</p>
       </div>
       <hr class="my-4">
       <div class="mb-4">
          <a href="{{ route('bundles.create') }}" class="btn"><i class="far fa-plus me-2"></i> Criar nova coleção</a>
       </div>
       @if($bundles->count() === 0)
         <p class="text-slate-400 text-center my-12">Nenhuma coleção de filas encotrada </p>
       @else 
        <table id="table-bundles">
             <thead class="bg-black text-white">
                   <tr>
                       <th>Nome</th>
                        <th>Número de filas</th>
                        <th>Credenciais</th>
                     <th></th>
                   </tr>
            </thead>

             <tbody>
                 @foreach($bundles as $bundle)
                   <tr class="{{ $bundle->deleted_at !== null ? 'text-red-500': '' }}" >
                        <td>{{ $bundle->name }}</td>
                         <td>{{ count(json_decode($bundle->queues)) }}</td>
                        <td>{{ $bundle->credential_password}}</td>
                        <td>
                           <div class="flex justify-end">
                             
                              @if($bundle->deleted_at === null)
                                <a href="{{ route('bundle.delete',['id'=> Crypt::encrypt($bundle->id) ])}}" class="btn-red me-2" ><i class="fa fa-trash-can"></i></a>
                                <a href="{{ route('bundle.edit',['id'=> Crypt::encrypt($bundle->id) ])}}" class="btn me-2" ><i class="far fa-edit"></i></a>
                              @else
                                 <a href="{{ route('bundle.restore',['id'=> Crypt::encrypt($bundle->id) ])}}" class="btn-white" ><i class="fa-solid fa-trash-arrow-up"></i></a>
                               
                              @endif
                           </div>

                           
                        </td>
                </tr>
              @endforeach
    </tbody>
</table>

         
       @endif
   </div>

   <script>




        $(document).ready(function(){

            $('#table-bundles').DataTable({
               //traduzindo os componetes pra portugues

               language:{
                  url:"{{ asset('assets/datatables/pt-PT.json') }}"
              }
            }
        );
          
        });


       
   </script>
</x-layouts.auth-layout>
