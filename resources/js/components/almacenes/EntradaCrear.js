import React from 'react'
import {Component} from 'react';
import ListProductComponent from './componentsExtras/listProductComponent';
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import { registerLocale, setDefaultLocale } from  "react-datepicker";
import format from 'date-fns/format';
import es from 'date-fns/locale/es';
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'

class EntradaCrear extends Component {
    constructor(){
        super();
        this.state ={

            contador:[],
            contadorNumero:0,
            nada:'',

            numerox:0,
            modo:0,
            nombreProducto:'',
            precioProducto:'',
            scopeNumero:'',
            cantidad:'',
            stock:'',
            importe:'',

            tasks:'',
            proveedores:[],
            //code
            code:'',
            //modales
            estadoModalProducto:false,
            //toasts
            estadoToastRed:false,
            estadoToastGreen:false,
            messageRed:'',
            messageGreen:'',

            //---ac
            idProducto:'',
            //
            nowday:'',

            //emitir
            idProveedor:'',
            idFactura:'',
            datepick:'',

            enviarCantidad:'',
            enviarPrecioProducto:'',
            enviarIdProducto:'',

            precio_array:[],
            cantidad_array:[],
            idProducto_array:[],
            //modal agregar productos
            idMarca:'',
            idTipo:'',
            precioVentaProducto:'',
            codigoProductoNuevo:'',
            nombreProductoNuevo:'',
            tasksM:[],
            tasksTP:[],
            tiposproductos_tb:[],
            marcas_tb:[],

            proveedores_t:[],
            nuevoTipoProducto:''
          }
        this.quitarOpcion = this.quitarOpcion.bind(this);
        this.fetchDatos = this.fetchDatos.bind(this);
        this.handleKeyDown=this.handleKeyDown.bind(this);
        this.handleKeyDown2=this.handleKeyDown2.bind(this);
        this.scope=this.scope.bind(this);
        this.handleChange=this.handleChange.bind(this);
        this.sendAgregarEntrada=this.sendAgregarEntrada.bind(this);
        this.handleChangeTime=this.handleChangeTime.bind(this);
        this.quitarScope=this.quitarScope.bind(this);

    }


    handleChangeTime (date){
        this.setState({
            datepick: format(date, 'yyyy-MM-dd'),
            nowday: date
        });
        console.log(this.state.datepick);
    };



//tiposProductos del modal
      sendAgregarTipoProducto(){

              fetch(`/almacen/tipo/crear`,
              {
                  method: 'POST',
                  body: JSON.stringify({
                      '_token': csrf_token,
                      nuevoTipoProducto: this.state.nuevoTipoProducto
                  }),
                  headers: {
                      'Accept' : 'application/json',
                      'Content-Type': 'application/json',

                  }
              }
          )
          .then(res =>res.json())
          .then(data => {
                if(data.response==true){
                  console.log("exito");
                  this.cambiarToastGreen("el tipo de producto",'true');
                  this.fetchAgregarTipo();
                }
                else{
                  console.log("error");
                  this.cambiarToastRed("en agregar Tipo producto",'true');
                }

          }).catch((error)=> {
            console.log('Hubo un problema con la petición Fetch:' + error.message);
            this.cambiarToastRed("en agregar Tipo producto",'true');
          });  }

    fetchAgregarTipo(){
              fetch('/almacen/tiposproductos/tb_tiposProductos')
                      .then(res => res.json())
                      .then(data => {
                          this.setState({tiposproductos_tb: data}, () => {
                              this.suprimirDatos();
                          });
              })
          }


//modall
suprimirDatos(operacion){
        let x = [];
        let y = [] ;
        let z = [];
        let b = [];
        let q = [];
        let r = [];
        let c = ()=>{
            this.state.marcas_tb.data ?
            this.state.marcas_tb.data.map(task=>{
               x.push({
                   nombre: task.nombre,
                   id: task.id
                });
            }) :null
            b = [ ...new Map(x.map(obj => [`${obj.nombre}:${obj.id}`, obj]))
            .values()];
            console.log(b);
            return b;
        }
        var d = ()=>{
            this.state.tiposproductos_tb.data ?
            this.state.tiposproductos_tb.data.map(task=>{
               y.push({
                  nombre: task.nombre,
                  id: task.id
                });
            }) :null
            q = [ ...new Map(y.map(obj => [`${obj.nombre}:${obj.id}`, obj]))
            .values()];
            return q;
        }
        var e = ()=>{
            this.state.proveedores.data ?
            this.state.proveedores.data.map(task=>{
               z.push(
                 task.id
                  );
            }) :null
            console.log(z);
            return z;
        }

        if(operacion=='op2'){
          this.setState({
              proveedores_t: e()
          },()=>this.operacionScope2())
        }
        else{
        this.setState({
            tasksM: c(),
            tasksTP: d(),
        },()=>this.operacionScope())
      }
    }
      operacionScope(){

        let x = this.state.tasksM[0];
        let y = this.state.tasksTP[0];

        if(x && y){
        let idMarca = x.id;
        let idTipo = y.id;

        this.setState({
          idMarca: idMarca,
          idTipo: idTipo
        },()=>console.log(this.state.idMarca, this.state.idTipo))
      }


      }
      operacionScope2(){

        let x = this.state.proveedores_t[0];

        let idProveedor = x;
        console.log(idProveedor);
        this.setState({
          idProveedor: idProveedor,
        },()=>console.log(this.state.idProveedor))


      }

//toasts y modales
      cambiarToastRed(message,accion) {
          if(accion=='true'){
          this.setState({
              messageRed: message,
              estadoToastRed: true
          });}
          else{
            this.setState({
                estadoToastRed: false
            });
          }

      }
      cambiarToastGreen(message,accion) {
          if(accion=='true'){
          this.setState({
              messageGreen: message,
              estadoToastGreen: true
          });}
          else{
            this.setState({
                estadoToastGreen: false
            });}
          }

      cambiarModalProducto() {
          this.setState({
              estadoModalProducto: !this.state.estadoModalProducto
          });
      }
      sendAgregarProducto(name){

          fetch(`/almacen/producto/crear`,
          {
              method: 'POST',
              body: JSON.stringify({
                  '_token': csrf_token,
                  precioVentaProducto:this.state.precioVentaProducto,
                  codigoProductoNuevo:this.state.codigoProductoNuevo,
                  marcaProducto:this.state.idMarca,
                  tipoProducto:  this.state.idTipo,
                  nombreProductoNuevo: this.state.nombreProductoNuevo
              }),
              headers: {
                  'Accept' : 'application/json',
                  'Content-Type': 'application/json',

              }
            }
            )
            .then(res =>res.json())
            .then(data => {
                if(data.response==true){
                  console.log("exito");
                  this.cambiarToastGreen("el producto",'true');
                  if(this.state.scopeNumero){
                    this.generateComponents(
                      this.state.nombreProductoNuevo,
                      this.state.scopeNumero,
                      0,
                      0,
                      data.idProducto,
                    );
                    this.cambiarModalProducto();
                  }
                  else{
                  this.generateComponents(
                      this.state.nombreProductoNuevo,
                      this.state.numerox,
                      0,
                      0,
                      data.idProducto,);
                  this.cambiarModalProducto();
                }

                }
                else{
                  console.log("error");
                  this.cambiarToastRed("en agregar producto",'true');
                }


            }).catch((error)=> {
              console.log('Hubo un problema con la petición Fetch:' + error.message);
              this.cambiarToastRed("en agregar producto",'true');
            });  }


//-----------------------------------------------------------|



    generateComponents(nombre,numero,precio,stock,idProducto){

        var a = [...this.state.contador];
        if(numero==0){
          console.log(a);
          a.push({
              numero: this.state.contadorNumero+1,
              nombre: '',
              precio: 0,
              stock:'',
              cantidad:'',
              idProducto:'',
              importe:''
          });
          this.setState({
            contador: a,
            nombreProducto: nombre,
            precioProducto: precio,
            stock: stock,
            cantidad: 0,
            idProducto: idProducto,
            contadorNumero: this.state.contadorNumero+1,
            numerox: this.state.numerox+1,
            modo: this.state.modo+1
          })

      }

        if(numero!==0){
          console.log(numero);
          a.push({
              numero: this.state.contadorNumero+1,
              nombre: '',
              precio: 0,
              stock:'',
              cantidad: '',
              idProducto:'',
              importe:''
          });
          a.forEach(function(obj){
            if(obj.numero == numero){
              obj.nombre = nombre;
              obj.precio = precio;
              obj.stock= stock;
              obj.idProducto = idProducto;
            }
          })
          console.log(a);
          this.setState({
            contador: a,
            contadorNumero: this.state.contadorNumero+1
          })

      }
    }

    quitarOpcion(numero){
        console.log(numero);

        var array =[...this.state.contador];
        var index = this.state.contador.findIndex(i=> i.numero === numero)
        if(index>-1){
          array.splice(index,1)
          var k = array.length;
          console.log(k);
          console.log(array)
          if(k==0){
            this.setState({
              numerox: 0,
              modo:0
            })
          }
          this.setState({
            contador: array,
          })
        }
    }

    fetchDatos(){
        fetch('/almacen/tb_almacen')
                .then(res => res.json())
                .then(data => {
                    this.setState({tasks: data},()=> console.log(this.state.tasks));

            })

        fetch('/almacen/proveedores/tb_proveedores')
                 .then(res => res.json())
                .then(data => {
                    this.setState({proveedores: data},()=> {
                      console.log(data);
                      this.suprimirDatos('op2');
                    });

            })
        fetch('/almacen/tiposproductos/tb_tiposProductos')
                        .then(res => res.json())
                        .then(data => {
                            this.setState({tiposproductos_tb: data});
                })

        fetch('/almacen/marcas/tb_marcas')
                .then(res => res.json())
                .then(data => {
                this.setState({marcas_tb: data});
            })
    }
    scope(numero){
      console.log(numero);
      this.setState({
        scopeNumero: numero
      })
    }
    quitarScope(){
        this.setState({
          scopeNumero: 0
        })
    }
    comprobarCode(name){
      fetch('/producto/buscar',
          {
              method: 'POST',
              body: JSON.stringify({
                  '_token': csrf_token,
                  codigoProducto: this.state.code
              }),
              headers: {
                  'Accept' : 'application/json',
                  'Content-Type': 'application/json',
                }
            }
                )

              .then(res => res.json())
              .then(data => {

                if(data.response == true){
                  console.log(data);
                  this.cambiarToastGreen("El producto ya existe",'true');
                  if(name=="productoEntradaChild"){
                    this.generateComponents(data.producto.nombre,this.state.scopeNumero,0,data.producto.cantidad
                    ,data.producto.id);
                  }
                  else{
                  this.generateComponents(data.producto.nombre,this.state.numerox,0,data.producto.cantidad
                  ,data.producto.id);
                }
              }
                else{
                  if(name=="productoEntradaChild"){
                    this.suprimirDatos();
                    this.cambiarModalProducto();
                  }
                  else{
                    this.quitarScope();
                    this.suprimirDatos();
                    this.cambiarModalProducto();
                  }

                }

          })
    }
    comprobarCodeProducto(){
      fetch('/producto/buscar',
          {
              method: 'POST',
              body: JSON.stringify({
                  '_token': csrf_token,
                  codigoProducto: this.state.code
              }),
              headers: {
                  'Accept' : 'application/json',
                  'Content-Type': 'application/json',
                }
            }
                )

              .then(res => res.json())
              .then(data => {
                if(data.response == true){
                  console.log(data);
                  this.cambiarToastRed("El producto ya existe",'true');
                }
                else{
                  console.log(data)
                  this.cambiarToastGreen("El producto no existe",'true');
                }

          })
    }
    operacionClean(){
      if(this.state.numerox==0){
                fetch(`/almacen/entrada/crear`,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token': csrf_token,
                        proveedor: this.state.idProveedor,
                        factura: this.state.idFactura,
                        fecha: this.state.datepick,

                        precio: this.state.enviarPrecioProducto,
                        cantidad: this.state.enviarCantidad,
                        producto: this.state.enviarIdProducto,


                    }),
                    headers: {
                        'Accept' : 'application/json',
                        'Content-Type': 'application/json',

                    }
                }
            )
            .then(res =>res.json())
            .then(data => {
                if(data.response==true){
                  console.log("exito");
                  this.cambiarToastGreen("la entrada",'true');
                  this.fetchDatos();
                  this.props.addContainer();
                }
                else{
                  console.log("error");
                  this.cambiarToastRed("en agregar entrada",'true');
                }

            }).catch((error)=> {
              console.log('Hubo un problema con la petición Fetch:' + error.message);
              this.cambiarToastRed("en agregar entrada",'true');
            });  }
      else{
              fetch(`/almacen/entrada/crear`,
              {
                  method: 'POST',
                  body: JSON.stringify({
                      '_token': csrf_token,
                      proveedor: this.state.idProveedor,
                      factura: this.state.idFactura,
                      fecha: this.state.datepick,
                      precio: this.state.precio_array,
                      cantidad: this.state.cantidad_array,
                      producto: this.state.idProducto_array,

                  }),
                  headers: {
                      'Accept' : 'application/json',
                      'Content-Type': 'application/json',

                  }
              }
          )
          .then(res =>res.json())
          .then(data => {
              if(data.response==true){
                console.log("exito");
                this.cambiarToastGreen("la entrada",'true');
                this.fetchDatos();
                this.props.addContainer();

              }
              else{
                console.log("error");
                this.cambiarToastRed("en agregar entrada",'true');
              }

          }).catch((error)=> {
            console.log('Hubo un problema con la petición Fetch:' + error.message);
            this.cambiarToastRed("en agregar entrada",'true');
          });  }

    }

    sendAgregarEntrada(){

      var x= [this.state.precioProducto];
      var y= [this.state.cantidad];
      var z= [this.state.idProducto];

      if(this.state.numerox==0){
        this.setState({
          enviarPrecioProducto: x,
          enviarCantidad: y,
          enviarIdProducto: z,
        }, ()=>{
          console.log(this.state.enviarPrecioProducto,this.state.enviarCantidad,this.state.enviarIdProducto);
          this.operacionClean();
        })
      }
      else{
        var data= [...this.state.contador];
        data.map((e)=>{
          if(e.idProducto!=''){
            x.push(e.precio);
            y.push(e.cantidad);
            z.push(e.idProducto);
          }
        })
        console.log(x,y,z);
        this.setState({
          precio_array: x,
          cantidad_array:y,
          idProducto_array:z
        },()=>{
          this.operacionClean();
        }
        )
      }
     }


    handleKeyDown(e){
      if(e.key === 'Enter'){
        console.log('hacer validacion');
        console.log(e.target.name);
        var name = e.target.name;
        this.setState({code: e.target.value},()=>this.comprobarCode(name));
      }
    }
    handleKeyDown2(e){
      if(e.key === 'Enter'){
        console.log('hacer validacion');
        console.log(e.target.name);
        var name = e.target.name;
        this.setState({code: e.target.value},()=>this.comprobarCodeProducto());
      }
    }

    handleChange (e){
           const {name , value} = e.target;
           if(name=='cantidadProducto'){
             var calculo = (this.state.precioProducto * value);
               this.setState({
                 cantidad: value,
                 importe: calculo
               })

           }
           if(name=='precioProducto'){
            var calculo = (this.state.cantidad * value);
              this.setState({
                precioProducto: value,
                importe: calculo
                })

          }

           if(name=='cantidadProductoChild'){
             var p = [...this.state.contador];
              p.forEach((a)=>{
                console.log(value);
                if(a.numero==this.state.scopeNumero){
                   a.cantidad = value;  a.importe = (a.precio * value);}
              })

                this.setState({
                  contador: p
                },()=>console.log(p))

           }
            if(name=='precioProductoChild'){
               var p = [...this.state.contador];
                p.forEach((a)=>{
                  console.log(value);
                  if(a.numero==this.state.scopeNumero){
                     a.precio = value; a.importe = (a.cantidad * value) ;}
                })

                  this.setState({
                    contador: p
                  },()=>console.log(p))

           }
           else{
             this.setState({
                 [name] : value

             })
           }

           console.log(name);
           console.log(value);

       }

    componentDidMount(){
        this.fetchDatos();
    }




    render(){
        return(
            <div className="col-lg-12 grid-margin stretch-card">
                    <div className="card card-default">
                        <div className="card-header cabezera">
                            <h4> Agregar Entrada </h4>
                        </div>
                        <div className="modal-body">
                            <div className="card-body">
                                    <input type='hidden' value='1' name= "cantidadProductos" id="cantidadProductos"/>
                                    <input type='hidden' value='0' name= "agregandoProveedor" id="agregandoProveedor"/>
                                    <input type='hidden' value='0' name= "agregandoProducto" id="agregandoProducto"/>

                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-6">
                                                <label>Selecciona al proveedor</label>
                                                <div>
                                                    <select className="form-control listProductos" name="idProveedor" id="proveedores" style={{width: '90%'}} onChange={this.handleChange} value={this.state.idProveedor}>

                                                        {
                                                            this.state.proveedores.data?
                                                            this.state.proveedores.data.map((data)=>{

                                                               return(
                                                                <option key={data.id} value={data.id} > {data.nombre} </option>
                                                               )
                                                            }
                                                            )
                                                            :null
                                                        }

                                                    </select>
                                                </div>
                                            </div>
                                            <div className="col-3">
                                                <label>Numero de factura</label>
                                                <input type="text" className="form-control" name="idFactura" id="factura" onChange={this.handleChange}/>
                                            </div>
                                            <div className="col-3">
                                                <label>Fecha</label><br/>
                                                <DatePicker locale="es" selected={this.state.nowday} onChange={this.handleChangeTime} />
                                             </div>

                                        </div>
                                    </div>
                                    <div className="form-group" id="listProductos" name="this.props.number" >
                                        <div className="row">
                                            <div className="col-2">
                                                <label>Producto de entrada</label><br/>
                                                <input type="text" name="productoEntrada" className="form-control" onKeyDown={this.handleKeyDown} />
                                            </div>
                                            <div className="col-2">
                                                <label>Nombre</label><br/>
                                                <input type="text" name="productoEntrada" className="form-control" value={this.state.nombreProducto}/>
                                            </div>
                                            <div className="col-2 precioCompraContainer">
                                                <label>Precio de compra</label>
                                                <input type="number" name="precioProducto" id="precioProducto" className="form-control precioCompra"
                                                    pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value={this.state.precioProducto} data-type="currency" placeholder="S/" onChange={this.handleChange}/>
                                            </div>
                                            <div className="col-2 cantidadCompraContainer">
                                                <label>Cantidad</label>
                                                <input type="number" className="form-control cantidadProductoEntrada" name="cantidadProducto" value={this.state.cantidad} id="cantidadProducto" onChange={this.handleChange}/>
                                            </div>
                                            <div className="col-1">
                                                <label>Stock</label>
                                                <input type="text" className="form-control cantidadProductoEntrada" name="stock" value={this.state.stock} />
                                            </div>
                                            <div className="col-2 importeCompraContainer">
                                                <label>Importe</label>
                                                <input type="text" name="importe[]" id="importe" className="form-control " pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value={this.state.importe} data-type="currency" placeholder="S/1,000,000.00"/ >
                                            </div>
                                            <div className="col-1">
                                                <br/>
                                                <button type="button" className="btn btn-warning btn-rounded btn-icon " id="agregarNuevoProducto" >
                                                    <i className="mdi mdi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                     {
                                        this.state.contador?
                                        this.state.contador.map((data)=>{
                                             return(
                                              <ListProductComponent key={data.numero} number={data.numero}  name={data.nombre} quitarOpcion={this.quitarOpcion} handleScope={this.scope} handleChange={this.handleChange} handleKeyDown={this.handleKeyDown}
                                               precioProductoChild={data.precio} cantidadProductoChild={data.cantidad} stock={data.stock}  idProducto={data.idProducto} importeChild={data.importe}/>
                                             )
                                           }
                                        )
                                        :null

                                     }


                                    <div className="form-group">
                                        <button type="button" id="agregarProducto" className="btn btn-primary btn-rounded btn-fw " onClick={()=>this.generateComponents()}>Agregar producto</button>
                                    </div>

                                    <div className="form-group boton">
                                        <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearEntrada" onClick={()=>this.sendAgregarEntrada()}>
                                            Agregar</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <Modal
                        show={this.state.estadoModalProducto}
                        onHide={() => this.cambiarModalProducto()}
                        >
                        <Modal.Header closeButton>
                          <Modal.Title id="example-custom-modal-styling-title">
                            Agregar Producto
                          </Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                        <div className="card card-default">
                           <div className="card-header cabezera">
                               <div className="form-group row">
                                               <div className="col-sm-6">
                                                       <div className="form-check">
                                                       <label className="form-check-label">
                                                           <input type="radio" className="form-check-input" name="productoEstado" id="cerrarProducto" value="1" defaultChecked=""/>
                                                           Cerrar automaticamente
                                                       <i className="input-helper"></i></label>
                                                       </div>
                                                   </div>
                                                   <div className="col-sm-6">
                                                       <div className="form-check">
                                                       <label className="form-check-label">
                                                           <input type="radio" className="form-check-input" name="productoEstado" id="abrirProducto" value="0"/>
                                                           Mantenerla abierta
                                                       <i className="input-helper"></i></label>
                                                       </div>
                                                   </div>
                                           </div>
                                   </div>
                               <div className="modal-body">
                                   <div className="card-body">
                                           <div className="form-group">
                                               <div className="row">
                                                   <div className="col-12">
                                                       <label>Nuevo tipo de producto</label>
                                                       <div className="input-group">
                                                           <input type="text" placeholder="Si no existe el tipo de producto agregalo" className="form-control" name="nuevoTipoProducto" id="nuevoTipoProducto" onChange={this.handleChange}  value={this.state.nuevoTipoProducto}/>
                                                           <div className="input-group-append">
                                                               <button  id="crearTipoProducto"  className="btn form-control btn-sm btn-primary" type="button" onClick={()=>this.sendAgregarTipoProducto()}><i className="mdi mdi-plus"></i></button>
                                                           </div>
                                                       </div>

                                                   </div>
                                               </div>
                                           </div>
                                           <div className="form-group" >
                                               <div className="row">
                                                   <div className="col-12">
                                                       <label>Codigo</label>
                                                       <input type="text" className="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo" value={this.state.codigoProductoNuevo} onChange={this.handleChange} onKeyDown={this.handleKeyDown2}/>
                                                   </div>
                                                   <div className="col-12" id="alertaCodigo">
                                                   </div>
                                               </div>
                                           </div>
                                           <div className="form-group">
                                               <div className="row">
                                                   <div className="col-6">
                                                       <label>Marcas</label>
                                                       <div className="input-group">
                                                           <select className="form-control" name="idMarca" id="marcaProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idMarca}>
                                                               {
                                                                   this.state.tasksM ?
                                                                   this.state.tasksM.map(element => {
                                                                       return (
                                                                           <option value={element.id}  >{element.nombre}</option>
                                                                           );
                                                                   })
                                                                   :null
                                                               }
                                                           </select>
                                                       </div>
                                                   </div>
                                                   <div className="col-6">
                                                       <label>Tipos de prodcuto</label>
                                                       <div className="input-group">
                                                           <select className="form-control" name="idTipo" id="tipoProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idTipo}>
                                                               {
                                                                   this.state.tasksTP ?
                                                                   this.state.tasksTP.map(element=>{
                                                                   return (
                                                                   <option value={element.id}>{element.nombre}</option>
                                                                   );
                                                               })
                                                                : null
                                                               }
                                                           </select>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                           <div className="form-group">
                                               <div className="row">
                                                   <div className="col-12">
                                                       <label>Nombre del producto</label>
                                                       <input type="text" className="form-control" name="nombreProductoNuevo" id="nombreProductoNuevo" onChange={this.handleChange} value={this.state.nombreProductoNuevo}/>
                                                   </div>
                                               </div>
                                           </div>
                                           <div className="form-group">
                                               <div className="row">

                                                 { /* <div className="col-6">
                                                       <label>Precio de venta</label>
                                                       <input type="text" name="precioVentaProductoSinIGV" id="precioVentaProductoSinIGV" className="form-control"
                                                           pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" defaultValue="" data-type="currency" placeholder="S/1,000,000.00"/>
                                                           </div> */}

                                                   <div className="col-6">
                                                       <label>Precio con IGV(18%)</label>
                                                       <input type="text" name="precioVentaProducto" id="precioVentaProducto" className="form-control"
                                                           pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" placeholder="S/1,000,000.00" onChange={this.handleChange} value={this.state.precioVentaProducto}/>
                                                   </div>
                                               </div>
                                           </div>


                                           <div className="form-group boton">
                                               <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto" onClick={()=>this.sendAgregarProducto()}>
                                                   Agregar</button>
                                           </div>
                                        </div>
                                   </div>
                               </div>
                        </Modal.Body>
                      </Modal>




                    <Toast
                          style={{
                          position: 'fixed',
                          top: 5,
                          right: 5,
                          zIndex:1060,
                          backgroundColor: 'rgba(76,208,76,0.7)',
                          color:'white'
                          }}
                          onClose={() => this.cambiarToastGreen()}
                          show={this.state.estadoToastGreen}
                          delay={3000}
                          autohide
                          >
                            <Toast.Body>
                              <h5>Se agrego satisfactoriamente {this.state.messageGreen}</h5>
                            </Toast.Body>
                      </Toast>

                    <Toast
                            style={{
                            position: 'fixed',
                            top: 5,
                            right: 5,
                            zIndex:1061,
                            backgroundColor: 'rgba(205,55,55,0.7)',
                            color:'white'
                              }}
                            onClose={() => this.cambiarToastRed()}
                            show={this.state.estadoToastRed}
                            delay={3000}
                            autohide

                      >
                        <Toast.Body>
                          <h5>Hubo un problema al agregar {this.state.messageRed}</h5>
                        </Toast.Body>
                    </Toast>


                </div>
)
        }}
export default EntradaCrear
