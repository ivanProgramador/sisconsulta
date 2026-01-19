<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-2 h-full">

        <div class="text-end mb-4">
            [opções]
        </div>
        
        
                    <div class="main-card flex gap-4 w-full">

                    <div class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl">

                      [Filas]
                        
                    </div>

                    <div class="flex w-1/3 h-100 border-1 border-slate-300 rounded-xl p-2">
                            [preview do ticket]
                    </div>
    



       

            
                
                     
        
        </div>





    </div>

    <script>
       
       //variavel que vai receber a rota 
       const url = "{{ route('dispenser.get.bundle.data', ['credential' => $credential ]) }}";

       //funcção que será executada de forma assincrona

       async function getBundleData(url){
          
           try{
             const response = await fetch(url);
              if(!response.ok){
                 throw new Error('Erro ao buscar dados: ' + response.status);
              }
              return await response.json();

            }catch(error){
              console.log(error);
              return null;
           }
       }

       getBundleData(url)
                 .then(data=>{
                    console.log(data);
                 })





    </script>

</x-layouts.guest-layout>