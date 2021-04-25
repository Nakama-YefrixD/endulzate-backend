import React from 'react';
import {Component} from 'react';
import Botones from './Botones'
import Tabla from './Tabla'
import Graficos from './Graficos'
import cogoToast from 'cogo-toast';

  class Reportes extends Component {

      constructor(){
          super();
          this.state ={

            reporte:[],

            codigoProducto:'',
            sucursalProducto:'',

            codigoProductoSpan:'',
            stockActual:'',
            nombreProducto:'',
            entradaTotal:'',
            transferenciaEnviadas:'',
            transferenciaRecibidas:'',
            totalVendido:'',

            //estados componentes

            addBotones:false,
            addTabla:false,
            addGraficos:false

          }
          this.fetchDataTable = this.fetchDataTable.bind(this);
          this.handleChange = this.handleChange.bind(this);
          this.generateGraficos = this.generateGraficos.bind(this);
          this.generateExcel = this.generateExcel.bind(this);
      }

      cleanInputs(op){

        }
      crearRegistros(){

        var reporte = this.state.reporte;

        this.setState({
          codigoProductoSpan:this.state.codigoProducto,
          stockActual:reporte.stockActual,
          nombreProducto:reporte.nombre,
          entradaTotal:reporte.entradasTotales,
          transferenciaEnviadas:reporte.transferEnviadas,
          transferenciaRecibidas:reporte.transferRecibidas,
          totalVendido:reporte.ventasTotales,
          sucursalProducto:1
        })

      }
      limpiarRegistros(){

        this.setState({
          codigoProductoSpan:'',
          stockActual:'',
          nombreProducto:'',
          entradaTotal:'',
          transferenciaEnviadas:'',
          transferenciaRecibidas:'',
          totalVendido:'',
          sucursalProducto:''
        })

      }

      sendCodigoProducto(){

        cogoToast.loading(
            <div>
                <h4>Buscando registros del producto</h4>
            </div>,
            {
                position: 'top-right'
            }

        )
        .then(() => {
                fetch(`/movimientos/reporte`,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token': csrf_token,
                        codigo: this.state.codigoProducto,
                    }),
                    headers: {
                        'Accept' : 'application/json',
                        'Content-Type': 'application/json',
                    }
                }
            )
            .then(res => res.json())
            .then(data => {
                if(data.response==true){
                  console.log(data);
                  this.setState({
                    reporte: data,
                    addBotones:true,
                    addTabla:true
                  },()=>this.crearRegistros())
                  console.log("exito");

                  cogoToast.success(
                      <div>
                          <h4>Registros encontrados</h4>
                      </div>,
                      {
                        position: 'top-right'
                      }
                  );
                }
                else{
                  this.setState({
                    addBotones:false,
                    addTabla:false
                  },()=>this.limpiarRegistros())
                  cogoToast.error(
                      <div>
                          <h4>Registros no encontrados</h4>
                      </div>,
                      {
                        position: 'top-right'
                      }
                  );

                }

            })
            .catch((error)=> {
              console.log('Hubo un problema con la petición Fetch:' + error.message);
                cogoToast.error(
                    <div>
                        <h4>Hubo un error al buscar producto</h4>
                    </div>,
                    {
                      position: 'top-right'
                    }
                  );
            });

      })
    }


    fetchDataTable(){

      fetch('/configuracion/usuarios/tb_sucursales')
        .then(res => res.json())
        .then(data => {
            this.setState({sucursales: data},()=>{
              console.log(this.state.sucursales)
              });
            })
    }

    generateExcel(){

      cogoToast.loading(
          <div>
              <h4>Generando Excel</h4>
          </div>,
          {
              position: 'top-right'
          }
        ).then(()=>{

              fetch(`/movimientos/reporte`,
              {
                  method: 'POST',
                  body: JSON.stringify({
                      '_token': csrf_token,
                      codigo: this.state.codigoProducto,
                      descargar: true,
                  }),
                  headers: {
                      'Accept' : 'application/json',
                      'Content-Type': 'application/json',
                  }
                }

              ).then(response=>response.blob())
              .then(data=>{
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(data);
                link.download = `${this.state.nombreProducto}-${this.state.codigoProducto}.xlsx`;
                link.click();
                  cogoToast.success(
                      <div>
                          <h4>Excel generado</h4>
                      </div>,
                      {
                        position: 'top-right'
                      }
                  );
                })
                .catch((error)=> {
                  console.log('Hubo un problema con la petición Fetch:' + error.message);
                  cogoToast.error(
                      <div>
                          <h4>HUBO UN PROBLEMA AL CANCELAR LA VENTA</h4>
                      </div>,
                      {
                        position: 'top-right'
                      }
                  );
                });

        })


      }

    generateGraficos(){
      console.log("graficos")
      this.setState({
        addTabla: false
      },()=>this.setState({
        addGraficos:true
      }))
    }

    handleChange (e){
      const {name , value} = e.target;
      this.setState({
          [name] : value

      })
      console.log(name);
      console.log(value);
    }

    componentDidMount(){
      this.fetchDataTable();
    }



   render (){
     return(
       <div className="row">

          <div className="col-lg-12 grid-margin stretch-card">
             <div className="card">
                 <div className="card-body">
                         <div className="row">
                             <div className="col-3">
                                 <label> Buscar producto </label>
                                 <input type="text" className="form-control" name="codigoProducto" onChange={this.handleChange} value={this.state.codigoProducto} />
                             </div>
                             <div className="col-3">
                                 <label>Seleccionar sucursal</label>
                                 <select className="form-control listProductos" name="sucursalProducto" onChange={this.handleChange} value={this.state.sucursalProducto}>

                                     {
                                         this.state.sucursales ?
                                         this.state.sucursales.map((data)=>{

                                            return(
                                             <option key={data.id} value={data.id} > {data.nombre} </option>
                                            )
                                         }
                                         )
                                         :null
                                     }

                                 </select>
                             </div>
                             <div className="col-2">
                                 <br/>
                                 <button type="button"
                                     onClick={()=>this.sendCodigoProducto()}
                                     className="btn waves-effect waves-light btn-info">BUSCAR</button>
                             </div>
                             <div className="col-2">
                                 <label>Stock actual</label>
                                 <h4 className="card-title"name="stockActual">{this.state.stockActual}</h4>
                             </div>
                             { this.state.addBotones && <Botones generateExcel={this.generateExcel} generateGraficos={this.generateGraficos} />}

                       </div>
                       <div className="row" >
                           <div className="col-3">
                               <label>Nombre del producto </label>
                               <h4 className="card-title" name="nombreProducto">{this.state.nombreProducto} </h4>
                           </div>
                           <div className="col-2">
                               <label>Codigo</label>
                               <h4 className="card-title" name="codigoProductoSpan">{this.state.codigoProductoSpan}</h4>
                           </div>
                           <div className="col-2">
                               <label>Entradas totales</label>
                               <h4 className="card-title" name="entradaTotal">{this.state.entradaTotal}</h4>
                           </div>
                           <div className="col-2">
                               <label>Trasnferencias Enviadas</label>
                               <h4 className="card-title" name="transferenciaTotal">{this.state.transferenciaEnviadas}</h4>
                           </div>
                           <div className="col-2">
                               <label>Trasnferencias Recibidas</label>
                               <h4 className="card-title"name="transferenciaTotal">{this.state.transferenciaRecibidas}</h4>
                           </div>
                           <div className="col-1">
                               <label>Total vendidos</label>
                               <h4 className="card-title" name="totalVendido">{this.state.totalVendido}</h4>
                           </div>
                     </div>

                 </div>
             </div>
         </div>

         {this.state.addGraficos && <Graficos reporte={this.state.reporte} />}
         {this.state.addTabla && <Tabla reporte={this.state.reporte} />}

         </div>

       )

     }
  }


export default Reportes
