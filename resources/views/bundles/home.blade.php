<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">
   <div class="main-card overflow-auto">
       <div class="flex justify-between items-center">
          <p class="title-2">Coleção de filas</p>
       </div>
       <hr class="my-4">
       <div class="mb-4">
          <a href="#" class="btn"><i class="far fa-plus me-2"></i> Criar nova coleção</a>
       </div>
       @if($bundles->empty())
         <p class="text-slate-400 text-center my-12">Nenhuma coleção de filas encotrada </p>
       @else 
         <table id="table-bundles">
             <thead class="bg-black text-white">
               <tr>
                   <th>Nome</th>
                   <th>Numero de filas</th>
                   <th>Credenciais</th>
                   <th></th>
               </tr>
               <tbody>
                   @foreach($bundles as $bundle)
                      <tr>

                         <td>[nome]</td>
                         <td>[numero de filas]</td>
                         <td>[credenciais]</td>
                         <td>[acoes]</td>
                         
                      </tr>
                   @endforeach
               </tbody>
                
             </thead>

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
</x-layout.auth-layout>
