import React from 'react';
import {Component} from 'react';
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import { registerLocale, setDefaultLocale } from  "react-datepicker";
import format from 'date-fns/format';
import es from 'date-fns/locale/es';
import EntradaCrear from  './EntradaCrear';
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'
import CerrarCaja from './componentsExtras/cerrarCaja'
registerLocale('es', es);



class Almacen extends Component{

    constructor(){
        super();
        this.state ={
            productos_tb: [],
            //data editar productos
            idProducto:'',
            codigoP:'',
            nombreM: '',
            nombreT: '',
            nombreP: '',
            precioP: '',
            idMarca:'',
            idTipo:'',

            cantidadP: '',
            tasksM:[],
            tasksTP:[],
            tiposproductos_tb:[],
            marcas_tb:[],

            idProveedor:'',
            idFactura:'',
            datepick:'',
            nowday:'',
            precio:'',
            cantidad:'',
            proveedores_tb:[],
            nuevoTipoProducto:'',

            precioVentaProducto:'',
            codigoProductoNuevo:'',
            nombreProductoNuevo:'',

            rucProveedor:'',
            RUCdatos:[],
            telefonoProveedor:'',
            nombreProveedor:'',
            direccionProveedor:'',
            tipoProveedor:'',

            //modales
            estadoModalProducto:false,
            estadoModalMarca:false,
            estadoModalProveedor:false,
            estadoModalEditarProducto:false,
            estadoModalCaja:false,
            estadoModalAbrirCaja:false,
            estadoModalCerrarCaja:false,
            //Toast
            estadoToastRed:false,
            estadoToastGreen:false,
            messageRed:'',
            messageGreen:'',
            //componente agregar entradas
            addContainer: false,
            //buscador y Paginate
            estadoBoton:[],
            estadoQuery:'',

            //Caja estados inputs
            codigoCaja:'',                      codigoProductoCaja:          '',
            idProductoCaja:'',                  nombreCaja:                  '',
            cantidadProductosCaja:'',           precioCaja:                  '',
            codeC:'',                           code:                        '',

            //abrir Cajas
            codigoAbrirCaja:'',                 nombreCajaAbrirCaja:         '',
            stockCajaAbrirCaja:'',              cantidadProductosAbrirCaja:  '',
            precioCajaAbrirCaja:'',             nombreProductoAbrirCaja:     '',
            stockProductoAbrirCaja:'',          precioProductoAbrirCaja:     '',
            cantidadAbrirCaja:'',               codeAbrirCaja:               '',

            //Cerrar cajas
            codigoCerrarCaja:'',                 nombreCajaCerrarCaja:         '',
            stockCajaCerrarCaja:'',              cantidadProductosCerrarCaja:  '',
            precioCajaCerrarCaja:'',             nombreProductoCerrarCaja:     '',
            stockProductoCerrarCaja:'',          precioProductoCerrarCaja:     '',
            cantidadCerrarCaja:'',               codeCerrarCaja:               ''


        };

        this.fetchTasks = this.fetchTasks.bind(this);
        this.editTask = this.editTask.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleChangeTime = this.handleChangeTime.bind(this);
        this.handleChangeRUC = this.handleChangeRUC.bind(this);
        this.suprimirDatos = this.suprimirDatos.bind(this);
        this.handleChangeBuscador= this.handleChangeBuscador.bind(this);
        this.handleKeyDown1=this.handleKeyDown1.bind(this);
        this.handleKeyDown2=this.handleKeyDown2.bind(this);
        this.handleKeyDown3=this.handleKeyDown3.bind(this);
        this.handleKeyDown4=this.handleKeyDown4.bind(this);
        this.handleKeyDown5=this.handleKeyDown5.bind(this);
        this.cambiarModalCerrarCaja= this.cambiarModalCerrarCaja.bind(this);
        this.add=this.add.bind(this);
        this.sendCerrarCaja=this.sendCerrarCaja.bind(this);

    }

    add() {
      this.setState({addContainer : !this.state.addContainer})
    }

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
    cambiarModalMarca(){
        this.setState({
            estadoModalMarca: !this.state.estadoModalMarca
        })
    }
    cambiarModalProveedor(){
        this.setState({
            estadoModalProveedor: !this.state.estadoModalProveedor
        })
    }
    cambiarModalEditarProducto(){
        this.setState({
            estadoModalEditarProducto: !this.state.estadoModalEditarProducto
        })
    }
    cambiarModalCaja(){
        this.setState({
            estadoModalCaja: !this.state.estadoModalCaja
        })
    }
    cambiarModalAbrirCaja(){
        this.setState({
            estadoModalAbrirCaja: !this.state.estadoModalAbrirCaja
        })
    }
    cambiarModalCerrarCaja(){
        this.setState({
            estadoModalCerrarCaja: !this.state.estadoModalCerrarCaja
        })
    }
    addComponentEntrada() {
      this.setState({addContainer : !this.state.addContainer})
    }


    cleanInputs(){

        this.setState({
            idProducto:'',
            codigoP:'',
            nombreM: '',
            nombreT: '',
            nombreP: '',
            precioP: '',
            //idMarca:'',
            //idTipo:'',
            cantidadP: '',
            idProveedor:'',
            idFactura:'',
            datepick:'',
            nowday:'',
            precio:'',
            cantidad:'',
            nuevoTipoProducto:'',
            precioVentaProducto:'',
            codigoProductoNuevo:'',
            nombreProductoNuevo:'',
            rucProveedor:'',
            telefonoProveedor:'',
            nombreProveedor:'',
            direccionProveedor:'',
            tipoProveedor:'',

            //cajas agregar
            codigoCaja:'',                      codigoProductoCaja:          '',
            idProductoCaja:'',                  nombreCaja:                  '',
            cantidadProductosCaja:'',           precioCaja:                  '',
            codeC:'',                           code:                        '',

            //abrir Cajas
            codigoAbrirCaja:'',                 nombreCajaAbrirCaja:         '',
            stockCajaAbrirCaja:'',              cantidadProductosAbrirCaja:  '',
            precioCajaAbrirCaja:'',             nombreProductoAbrirCaja:     '',
            stockProductoAbrirCaja:'',          precioProductoAbrirCaja:     '',
            cantidadAbrirCaja:'',               codeAbrirCaja:               '',
            //Cerrar cajas
            codigoCerrarCaja:'',                 nombreCajaCerrarCaja:         '',
            stockCajaCerrarCaja:'',              cantidadProductosCerrarCaja:  '',
            precioCajaCerrarCaja:'',             nombreProductoCerrarCaja:     '',
            stockProductoCerrarCaja:'',          precioProductoCerrarCaja:     '',
            cantidadCerrarCaja:'',               codeCerrarCaja:               ''
        })

    }

    fechAgregarTipo(){
        fetch('/almacen/tiposproductos/tb_tiposProductos')
                .then(res => res.json())
                .then(data => {
                    this.setState({tiposproductos_tb: data}, () => {
                        this.suprimirDatos();
                    });
        })
    }

    fetchRUC(){
        fetch(`/consult/ruc/${this.state.rucProveedor}`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',
                }
            }
        )

        .then(res => res.json())
        .then(data => {
            console.log(data);
            this.setState({RUCdatos: data}, () => {
                this.datosdeRUC();
            });
    })
}

    datosdeRUC(){

        this.state.RUCdatos ?
                    this.setState({
                        telefonoProveedor:this.state.RUCdatos.persona.telefonos[0],
                        nombreProveedor:this.state.RUCdatos.persona.razonSocial,
                        direccionProveedor:this.state.RUCdatos.persona.direccion,
                        tipoProveedor:this.state.RUCdatos.persona.tipo
                },console.log(this.state.RUCdatos)) :null
    }

    fetchTasks(){
            fetch('/almacen/tb_almacen')
                .then(res => res.json())
                .then(data => {
                    this.setState({productos_tb: data},()=>this.agregarBotones());
                    console.log(this.state.productos_tb);

            })

            fetch('/almacen/proveedores/tb_proveedores')
                        .then(res => res.json())
                        .then(data => {
                            this.setState({proveedores_tb: data});

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

    suprimirDatos(){
            let x = [];
            let y = [] ;
            let b = [];
            let q= [];
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


            this.setState({
                tasksM: c(),
                tasksTP: d()
            },()=>this.operacionScope())
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



    editTask(idP,codeP,nameM, nameT,nameP,precioP,idM,idT){
        console.log(codeP,nameM, nameT,nameP,precioP,idM,idT);
        let x = [];
        let y = [] ;
        let b = [];
        let q= [];
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


        this.setState({
            idProducto: idP,
            codigoP: codeP,
            nombreM: nameM,
            nombreT: nameT,
            nombreP: nameP,
            precioP: precioP,
            idMarca: idM,
            idTipo:idT,
            tasksM: c(),
            tasksTP: d()
        })

      }

      sendAgregarEntrada(){

            console.log(this.state.idProveedor,
                this.state.idFactura,
                this.state.datepick,
                this.state.precio,
                 this.state.cantidad,
                this.state.idProducto)
            fetch(`/almacen/entrada/crear`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    proveedor: this.state.idProveedor,
                    factura: this.state.idFactura,
                    fecha: this.state.datepick,
                    precio: this.state.precio,
                    cantidad: this.state.cantidad,
                    producto: this.state.idProducto,
                    cantidadProducto: 1

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
              this.cambiarToastGreen("Se agrego con exito la entrada",'true');
              this.fetchTasks();
            }
            else{
              console.log("error");
              this.cambiarToastRed("Hubo un error al agregar entrada",'true');
            }

        }).catch((error)=> {
          console.log('Hubo un problema con la petición Fetch:' + error.message);
          this.cambiarToastRed("Hubo un error al agregar entrada",'true');
        });  }

    sendAgregarProducto(){

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
                this.cambiarToastGreen("Se agrego correctamente el producto",'true');
                this.fetchTasks();
                this.cleanInputs();
              }
              else{
                console.log("error");
                this.cambiarToastRed("Hubo un error al agregar producto",'true');
              }


          }).catch((error)=> {
            console.log('Hubo un problema con la petición Fetch:' + error.message);
            this.cambiarToastRed("Hubo un error al agregar producto",'true');
          });  }

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
                this.cambiarToastGreen("Se agrego correctamente el tipo de producto",'true');
                this.fechAgregarTipo();
              }
              else{
                console.log("error");
                this.cambiarToastRed("Hubo un error al agregar Tipo producto",'true');
              }

        }).catch((error)=> {
          console.log('Hubo un problema con la petición Fetch:' + error.message);
          this.cambiarToastRed("Hubo un error al agregar Tipo producto",'true');
        });  }


    comprobarCodeCaja(){
          fetch('/producto/buscar/caja',
              {
                  method: 'POST',
                  body: JSON.stringify({
                      '_token': csrf_token,
                      codigoCajaProducto: this.state.codeC
                  }),
                  headers: {
                      'Accept' : 'application/json',
                      'Content-Type': 'application/json',
                    }
                }
                    )

                  .then(res => res.json())
                  .then(data => {
                    if(data.response == true && data.existe == true){
                      console.log(data);
                      this.cambiarToastGreen(data.mensaje,'true');
                      this.setState({
                        cantidadProductosCaja: data.producto.cantidad,
                        nombreCaja: data.producto.nombre,
                        idMarca: data.producto.marca_id,
                        idTipo: data.producto.tipo_id,
                        precioCaja:data.producto.precio,
                      })
                        }
                    else if(data.response == true && data.existe == false){
                      console.log(data);
                      this.cambiarToastGreen(data.mensaje,'true');
                    }
                    else{
                      console.log(data)
                      this.cambiarToastRed(data.mensaje,'true');
                    }

                  })
        }

    sendAgregarCaja(){

              fetch(`/almacen/caja/crear`,
              {
                  method: 'POST',
                  body: JSON.stringify({
                      '_token': csrf_token,
                      codigoCaja: this.state.codigoCaja,
                      idProducto: this.state.idProductoCaja,
                      tipoProducto: this.state.idTipo,
                      marcaProducto:  this.state.idMarca,
                      cantidadProducto: this.state.cantidadProductosCaja,
                      nombreCaja: this.state.nombreCaja,
                      precioCaja: this.state.precioCaja

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
                console.log("exito");
                this.cambiarToastGreen("Se agrego correctamente la caja",'true');
                this.cleanInputs();
                this.fetchTasks();

              }
              else{
                console.log("error");
                this.cambiarToastRed("Hubo un error al agregar la caja",'true');
              }

          })
          .catch((error)=> {
            console.log('Hubo un problema con la petición Fetch:' + error.message);
            this.cambiarToastRed("Hubo un error al agregar la caja",'true');
          });  }


    sendAgregarMarca(){

            fetch(`/almacen/marca/crear`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    nombreMarca: this.state.nombreMarca
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
              console.log("exito");
              this.cambiarToastGreen("Se agrego correctamente la marca",'true');
              this.fetchTasks();
            }
            else{
              console.log("error");
              this.cambiarToastRed("Hubo un error al agregar la marca",'true');
            }

        })
        .catch((error)=> {
          console.log('Hubo un problema con la petición Fetch:' + error.message);
          this.cambiarToastRed("Hubo un error al agregar la marca",'true');
        });  }

    sendAgregarProveedor(){
            fetch('/almacen/proveedor/crear',
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token': csrf_token,
                        rucProveedor:this.state.rucProveedor,
                        telefonoProveedor:this.state.telefonoProveedor,
                        nombreProveedor:this.state.nombreProveedor,
                        direccionProveedor:this.state.direccionProveedor,
                        tipoProveedor:this.state.tipoProveedor
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
                  this.cambiarToastGreen("Se agrego correctamente el proveedor",'true');
                }
                else{
                  console.log("error");
                  this.cambiarToastRed("Hubo un error al agregar proveedor",'true');
                }
            })
            .catch((error)=> {
              console.log('Hubo un problema con la petición Fetch:' + error.message);
              this.cambiarToastRed("Hubo un error al agregar proveedor",'true');
            });  }

    sendAbrirCaja(){

            fetch(`/almacen/caja/abrir`,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token': csrf_token,
                        codigoCaja: this.state.codigoAbrirCaja,
                        numAbrir: this.state.cantidadAbrirCaja,

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
                  console.log("exito");
                  this.cambiarToastGreen("Se abrio correctamente la caja",'true');
                  this.cleanInputs();
                  this.fetchTasks();
                }
                else{
                  console.log("error");
                  this.cambiarToastRed("Hubo un error al abrir la caja",'true');
                }

            })
            .catch((error)=> {
              console.log('Hubo un problema con la petición Fetch:' + error.message);
              this.cambiarToastRed("Hubo un error al abrir la caja",'true');
            });  }


    comprobarCodeAbrirCaja(){
        fetch('/almacen/caja/consulta',
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    codigoCaja: this.state.codeAbrirCaja
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',
                  }
              })

                .then(res => res.json())
                .then(data => {
                  if(data.response == true){
                    console.log(data);
                    this.setState({
                      nombreCajaAbrirCaja:data.CAJA.nombre,
                      stockCajaAbrirCaja:data.CAJA.stock,
                      precioCajaAbrirCaja:data.CAJA.precio,
                      cantidadProductosAbrirCaja:data.CAJA.cantidad,
                      nombreProductoAbrirCaja:data.PRODUCTO.nombre,
                      stockProductoAbrirCaja:data.PRODUCTO.stock,
                      precioProductoAbrirCaja:data.PRODUCTO.precio
                    },()=>console.log("entregado"))
                    this.cambiarToastGreen("Es una caja",'true');
                  }
                  else{
                    console.log(data)
                    this.cambiarToastRed("No es una caja",'true');
                  }

                })
      }
      sendCerrarCaja(){

              fetch(`/almacen/caja/cerrar`,
                  {
                      method: 'POST',
                      body: JSON.stringify({
                          '_token': csrf_token,
                          codigoCaja: this.state.codigoCerrarCaja,
                          numAbrir: this.state.cantidadCerrarCaja,

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
                    console.log("exito");
                    this.cambiarToastGreen("Se cerro correctamente la caja",'true');
                    this.cleanInputs();
                    this.fetchTasks();
                  }
                  else{
                    console.log("error");
                    this.cambiarToastRed("Hubo un error al cerrar la caja",'true');
                  }

              })
              .catch((error)=> {
                console.log('Hubo un problema con la petición Fetch:' + error.message);
                this.cambiarToastRed("Hubo un error al abrir la caja",'true');
              });  }


      comprobarCodeCerrarCaja(){
          fetch('/almacen/caja/consulta',
              {
                  method: 'POST',
                  body: JSON.stringify({
                      '_token': csrf_token,
                      codigoCaja: this.state.codeCerrarCaja
                  }),
                  headers: {
                      'Accept' : 'application/json',
                      'Content-Type': 'application/json',
                    }
                })

                  .then(res => res.json())
                  .then(data => {
                    if(data.response == true){
                      console.log(data);
                      this.setState({
                        nombreCajaCerrarCaja:data.CAJA.nombre,
                        stockCajaCerrarCaja:data.CAJA.stock,
                        precioCajaCerrarCaja:data.CAJA.precio,
                        cantidadProductosCerrarCaja:data.CAJA.cantidad,
                        nombreProductoCerrarCaja:data.PRODUCTO.nombre,
                        stockProductoCerrarCaja:data.PRODUCTO.stock,
                        precioProductoCerrarCaja:data.PRODUCTO.precio
                      },()=>console.log("entregado"))
                      this.cambiarToastGreen("Es una caja",'true');
                    }
                    else{
                      console.log(data)
                      this.cambiarToastRed("No es una caja",'true');
                    }

                  })
        }

    sendEditarProducto(){

        fetch(`/almacen/producto/editar`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token': csrf_token,
                editarIdProducto: this.state.idProducto,
                editarCodigoProductoNuevo: this.state.codigoP,
                editarMarcaProducto: this.state.idMarca,
                editarTipoProducto: this.state.idTipo,
                editarNombreProductoNuevo: this.state.nombreP,
                editarPrecioVentaProducto: this.state.precioP


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
                console.log("exito");
                this.cambiarToastGreen("Se edito correctamente el producto",'true');
                this.fetchTasks();
              }
              else{
                console.log("error");
                this.cambiarToastRed("Hubo un error al editar producto",'true');
              }

        }).catch((error)=> {
          console.log('Hubo un problema con la petición Fetch:' + error.message);
          this.cambiarToastRed("Hubo un error al editar producto",'true');
        });  }

    deleteProducto(id){
        console.log(id);
            fetch(`/producto/eliminar`,{
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    id: id
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-type' : 'application/json'
                }
            })
            .then(res => res.json())
            .then(data =>{
                if(data.response==true){
                  console.log("exito");
                  this.cambiarToastGreen("Se elimino el producto",'true');
                  this.fetchTasks();
                }
                else{
                  console.log("error");
                  this.cambiarToastRed("Error en eliminar el producto",'true');
                }
            })
            .catch((error)=> {
              console.log('Hubo un problema con la petición Fetch:' + error.message);
              this.cambiarToastRed("Error en eliminar el producto",'true');
            });  }


//buscador------------------------------------|

      agregarBotones(){
          let z = this.state.productos_tb.last_page
          console.log(z);
          let x = [];
          if(z){

          for(let i = 1 ; i <= z ; i++){
            x.push(i);
          }
          this.setState({estadoBoton: x},()=>console.log(this.state.estadoBoton))
          }
        }

      cambiarPaginate(number){
          console.log(number);
          fetch(`/almacen/tb_almacen?page=${number}&buscar=${this.state.estadoQuery}`)
            .then(res => res.json())
            .then(data => {
                this.setState({productos_tb: data},()=>{
                  console.log(this.state.productos_tb)
                  this.agregarBotones();
                });

          })

        }


      fetchQuery(query,name){
            fetch(`/almacen/tb_almacen?${name}=${query}`)
              .then(res => res.json())
              .then(data => {
                this.setState({productos_tb: data,estadoQuery: query}, () => {
                    this.agregarBotones();
                    console.log("obtenido?");
                });
              })
        }

      handleChangeBuscador (e){
          const {name , value} = e.target;
          console.log(value);
          this.fetchQuery(value,name);
          console.log(name);

        }



// |----------------------------------------|

     handleChange (e){
            const {name , value} = e.target;
            this.setState({
                [name] : value

            })
            console.log(name);
            console.log(value);
            console.log(this.state.idProveedor);

        }
    handleChangeRUC(e){
        const {name , value} = e.target;

        if(value.length == 11){
            this.setState({
                [name] : value

            }, () => {
                this.fetchRUC();
            })
            console.log(value);
        }

    }

     handleChangeTime (date){
         this.setState({
             datepick: format(date, 'yyyy-MM-dd'),
             nowday: date
         });
         console.log(this.state.datepick);
     };
    componentDidMount(){
        this.fetchTasks();
        console.log(csrf_token);
    }

//comprobar ......
    handleKeyDown2(e){
      if(e.key === 'Enter'){
        console.log('hacer validacion');
        this.comprobarCodeProducto();
      }
    }
    handleKeyDown1(e){
      if(e.key === 'Enter'){
        console.log('hacer validacion');
        console.log(e.target.name);
        var name = e.target.name;
        this.setState({code: e.target.value},()=>this.comprobarCodeProductoCaja());
      }
    }
    handleKeyDown3(e){
      if(e.key === 'Enter'){
        console.log('hacer validacion');
        console.log(e.target.name);
        this.setState({codeC: e.target.value},()=>this.comprobarCodeCaja());
      }
    }
    handleKeyDown4(e){
      if(e.key === 'Enter'){
        console.log('hacer validacion');
        console.log(e.target.name);
        this.setState({codeAbrirCaja: e.target.value},()=>this.comprobarCodeAbrirCaja());
        }
    }
    handleKeyDown5(e){
      if(e.key === 'Enter'){
        console.log('hacer validacion');
        this.setState({codeCerrarCaja: e.target.value},()=>this.comprobarCodeCerrarCaja());
        }
      }
    comprobarCodeProducto(){
        fetch('/producto/buscar',
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    codigoProducto: this.state.codigoProductoNuevo
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

    comprobarCodeProductoCaja(){
        fetch('/producto/buscar/caja',
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    codigoCajaProducto: this.state.code
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',
                  }
              }
                  )

                .then(res => res.json())
                .then(data => {
                  if(data.response == true && data.existe == true){
                    console.log(data);
                    this.cambiarToastGreen("El producto ya existe",'true');
                    this.setState({idProductoCaja: data.producto.id});
                  }
                  else if(data.response == true && data.existe == false){
                    console.log(data);
                    this.cambiarToastRed("El producto no existe.Agregalo",'true');
                    this.cambiarModalCaja();
                    this.cambiarModalProducto();
                  }
                  else{
                    console.log(data)
                    this.cambiarToastRed(data.mensaje,'true');

                  }

            })
      }

    render(){
        return(
            <div className="row">

            <div className="col-lg-12 grid-margin stretch-card">
                <div className="card">
                    <div className="card-body">
                    <h4 className="card-title">Modulos de agregar</h4>
                    <div className="button-group">
                        <button type="button" className="btn waves-effect waves-light btn-primary" onClick={() => this.addComponentEntrada()}>Agregar Entrada</button>
                        <button type="button" className="btn waves-effect waves-light btn-success" onClick={()=>{this.suprimirDatos();this.cambiarModalProducto()}}>Agregar Producto</button>
                        <button type="button" className="btn waves-effect waves-light btn-danger" onClick={()=>{this.suprimirDatos();this.cambiarModalCaja()}}>Agregar Caja</button>
                        <button type="button" className="btn waves-effect waves-light btn-dark" onClick={()=>this.cambiarModalAbrirCaja()}>Abrir Caja</button>
                        <button type="button" className="btn waves-effect waves-light btn-light" onClick={()=>this.cambiarModalCerrarCaja()}>Cerrar Caja</button>
                        <button type="button" className="btn waves-effect waves-light btn-info" onClick={()=>this.cambiarModalMarca()}>Agregar Marca</button>
                        <button type="button" className="btn waves-effect waves-light btn-warning" onClick={()=>this.cambiarModalProveedor()}>Agregar Proveedor</button>

                    </div>
                    </div>
                </div>
            </div>

            { this.state.addContainer && <EntradaCrear addContainer={this.add} />}

        <div className="col-lg-12 grid-margin stretch-card">
            <div className="card">
                <div className="card-body">
                 <h4 className="card-title">Buscar</h4>
                  <div className="row">
                      <div className="col-3">
                          <label>Buscar Codigo</label>
                          <input type="text" className="form-control form-control-lg" name="bcodigo" id="buscar_tb_codigo" onChange={this.handleChangeBuscador}/>
                      </div>
                      <div className="col-3">
                          <label>Buscar Marca</label>
                          <input type="text" className="form-control form-control-lg" name="bmarca" id="buscar_tb_marca" onChange={this.handleChangeBuscador} />
                      </div>
                      <div className="col-3">
                          <label>Buscar Tipo</label>
                          <input type="text" className="form-control form-control-lg" name="btipo" id="buscar_tb_tipo" onChange={this.handleChangeBuscador}/>
                      </div>
                      <div className="col-3">
                          <label>Buscar Nombre</label>
                          <input type="text" className="form-control form-control-lg" name="bnombre" id="buscar_tb_nombre" onChange={this.handleChangeBuscador}/>
                      </div>

                </div>
            </div>
        </div>
    </div>

<div className="col-lg-12 grid-margin stretch-card">
    <div className="card">
        <div className="card-body">
            <h4 className="card-title">Almacen de productos</h4>
            <table id="tb_almacen" className="table table-striped" style={{width: '100%'}}>
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        this.state.productos_tb.data ?
                        this.state.productos_tb.data.map(task =>{
                            return (
                                <tr key={task.codigoProducto}>
                                    <td>{task.codigoProducto}</td>
                                    <td>{task.nombreMarca}</td>
                                    <td>{task.nombreTipo}</td>
                                    <td>{task.nombreProducto}</td>
                                    <td>{task.precioProducto}</td>
                                    <td>{task.cantidadProducto}</td>
                                    <td>
                                     <button onClick={()=> this.deleteProducto(task.idProducto)}
                                        className="btn btn-sm btn-danger eliminar" >
                                         <i className="mdi mdi-delete"> </i>
                                     </button>
                                     <button
                                        onClick={()=>{ this.editTask(task.idProducto,task.codigoProducto,task.nombreMarca,
                                        task.nombreTipo,task.nombreProducto,task.precioProducto,task.idMarca,task.idTipo); this.cambiarModalEditarProducto()}}
                                        className="btn btn-sm btn-secondary editar" data-target="#productoEditarModal">
                                         <i className="mdi mdi-lead-pencil">  </i>
                                     </button>
                                    </td>
                                </tr>
                            );
                        } )   : null
                    }
              </tbody>
            </table>
              <div className="container">
                  <div className="row justify-content-end">
                    <div className="col-4">

                      {
                        this.state.estadoBoton?
                        this.state.estadoBoton.map(task =>{
                            return (
                                <button key={task}
                                    className="btn btn-sm btn-secondary editar" type="button" onClick={()=>this.cambiarPaginate(task)}>{task}</button>

                            );
                         } )   : null
                       }
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>

     <CerrarCaja  estadoModalCerrarCaja={this.state.estadoModalCerrarCaja} cambiarModalCerrarCaja={this.cambiarModalCerrarCaja}

     codigoCerrarCaja={this.state.codigoCerrarCaja}  nombreCajaCerrarCaja={this.state.nombreCajaCerrarCaja}
     stockCajaCerrarCaja={this.state.stockCajaCerrarCaja} cantidadProductosCerrarCaja={this.state.cantidadProductosCerrarCaja}
     precioCajaCerrarCaja={this.state.precioCajaCerrarCaja} nombreProductoCerrarCaja={this.state.nombreProductoCerrarCaja}
     stockProductoCerrarCaja={this.state.stockProductoCerrarCaja} precioProductoCerrarCaja={this.state.precioProductoCerrarCaja}
     cantidadCerrarCaja={this.state.cantidadCerrarCaja}
     sendCerrarCaja={this.sendCerrarCaja}
     handleChange={this.handleChange}
     handleKeyDown5={this.handleKeyDown5}

     />

    <Modal
     show={this.state.estadoModalCaja}
     onHide={() => this.cambiarModalCaja()}

    >
        <Modal.Header closeButton>
          <Modal.Title id="example-custom-modal-styling-title">
            Agregar Caja
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
        <div className="card card-default">
               <div className="modal-body">
                   <div className="card-body">
                           <div className="form-group">
                               <div className="row">
                                   <div className="col-12">
                                       <label>Codigo de caja</label>
                                       <div className="input-group">
                                           <input type="text" className="form-control" name="codigoCaja" onChange={this.handleChange}  value={this.state.codigoCaja} onKeyDown={this.handleKeyDown3}/>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div className="form-group">
                               <div className="row">
                                   <div className="col-12">
                                       <label>Codigo de producto</label>
                                       <div className="input-group">
                                           <input type="text" className="form-control" name="codigoProductoCaja" onChange={this.handleChange}  value={this.state.codigoProductoCaja} onKeyDown={this.handleKeyDown1}/>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div className="form-group">
                               <div className="row">
                                   <div className="col-12">
                                       <label>Cantidad de productos en esta caja</label>
                                       <div className="input-group">
                                           <input type="text" className="form-control" name="cantidadProductosCaja" onChange={this.handleChange}  value={this.state.cantidadProductosCaja}/>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div className="form-group">
                               <div className="row">
                                   <div className="col-12">
                                       <label>Nombre de caja</label>
                                       <div className="input-group">
                                           <input type="text" className="form-control" name="nombreCaja" onChange={this.handleChange}  value={this.state.nombreCaja}/>
                                       </div>
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
                                       <label>Precio de caja con IGV(18%)</label>
                                       <div className="input-group">
                                           <input type="text" className="form-control" name="precioCaja" onChange={this.handleChange}  value={this.state.precioCaja}/>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div className="form-group boton">
                               <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto" onClick={()=>this.sendAgregarCaja()}>
                                   Agregar</button>
                           </div>
                      </div>
               </div>
           </div>

        </Modal.Body>
      </Modal>

    <Modal
       size='lg'
       show={this.state.estadoModalAbrirCaja}
       onHide={() => this.cambiarModalAbrirCaja()}

      >
          <Modal.Header closeButton>
            <Modal.Title id="example-custom-modal-styling-title">
              Abrir Caja
            </Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <div className="card card-default">
                 <div className="modal-body">
                     <div className="card-body">
                             <div className="form-group">
                                 <div className="row">
                                     <div className="col-12">
                                         <label>Codigo de caja</label>
                                         <div className="input-group">
                                             <input type="text" className="form-control" name="codigoAbrirCaja" onChange={this.handleChange}  value={this.state.codigoAbrirCaja} onKeyDown={this.handleKeyDown4}/>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div className="form-group">
                                 <div className="row">
                                     <div className="col-4">
                                         <label>Nombre de Caja</label>
                                         <h4 className="card-title"> {this.state.nombreCajaAbrirCaja} </h4>
                                     </div>
                                     <div className="col-2">
                                         <label>Stock</label>
                                         <h4 className="card-title"> {this.state.stockCajaAbrirCaja} </h4>
                                     </div>
                                     <div className="col-4">
                                         <label>Cantidad de productos dentro</label>
                                         <h4 className="card-title"> {this.state.cantidadProductosAbrirCaja} </h4>
                                     </div>
                                     <div className="col-2">
                                         <label>Precio</label>
                                         <h4 className="card-title"> S/{this.state.precioCajaAbrirCaja} </h4>
                                     </div>
                                 </div>
                             </div>
                             <div className="form-group">
                                 <div className="row">
                                     <div className="col-6">
                                         <label>Nombre del producto</label>
                                         <h4 className="card-title"> {this.state.nombreProductoAbrirCaja} </h4>
                                     </div>
                                     <div className="col-4">
                                         <label>Stock</label>
                                         <h4 className="card-title"> {this.state.stockProductoAbrirCaja} </h4>
                                     </div>
                                     <div className="col-2">
                                         <label>Precio</label>
                                         <h4 className="card-title"> S/{this.state.precioProductoAbrirCaja} </h4>
                                     </div>
                                 </div>
                             </div>


                             <div className="form-group">
                                 <div className="row">
                                     <div className="col-12">
                                         <label>Cuantas cajas desea abrir</label>
                                         <div className="input-group">
                                             <input type="text" className="form-control" name="cantidadAbrirCaja" onChange={this.handleChange}  value={this.state.cantidadAbrirCaja}/>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div className="form-group boton">
                                 <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" onClick={()=>this.sendAbrirCaja()}>
                                     Abrir Caja</button>
                             </div>
                        </div>
                 </div>
             </div>

          </Modal.Body>
      </Modal>

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
                                            <input type="text"      placeholder="Si no existe el tipo de producto agregalo" className="form-control" name="nuevoTipoProducto" id="nuevoTipoProducto" onChange={this.handleChange}  value={this.state.nuevoTipoProducto}/>
                                            <div className="input-group-    append">
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
                                        <input type="text" className="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo" value={this.state.codigoProductoNuevo} onKeyDown={this.handleKeyDown2} onChange={this.handleChange}/>
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

      <Modal

        show={this.state.estadoModalMarca}
        onHide={() => this.cambiarModalMarca()}
        >
        <Modal.Header closeButton>
          <Modal.Title id="example-custom-modal-styling-title">
            Agregar Marca
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
             <div className="card card-default">
                <div className="card-header cabezera">
                    <div className="form-group row">
                          <div className="col-sm-6">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="marcaEstado" id="cerrarMarca" value="1" defaultChecked=""/>
                                Cerrar automaticamente
                              <i className="input-helper"></i></label>
                            </div>
                          </div>
                          <div className="col-sm-6">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="marcaEstado" id="abrirMarca" value="0"/>
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
                                        <label>Nombre de la marca</label>
                                        <input type="text" className="form-control" name="nombreMarca" id="nombreMarca" onChange={this.handleChange}/>
                                    </div>
                                </div>
                            </div>


                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearMarca" onClick={()=>this.sendAgregarMarca()}>
                                    Agregar</button>
                            </div>
                    </div>
                </div>
            </div>

        </Modal.Body>
      </Modal>

      <Modal

        show={this.state.estadoModalProveedor}
        onHide={() => this.cambiarModalProveedor()}
        >
        <Modal.Header closeButton>
          <Modal.Title id="example-custom-modal-styling-title">
             Agregar proveedor
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
            <div className="card card-default">
                <div className="card-header cabezera">
                    <div className="form-group row">
                        <div className="col-sm-6">
                        <div className="form-check">
                            <label className="form-check-label">
                            <input type="radio" className="form-check-input" name="proveedorEstado" id="cerrarProveedor" value="1" defaultChecked=""/>
                            Cerrar automaticamente
                            <i className="input-helper"></i></label>
                        </div>
                        </div>
                        <div className="col-sm-6">
                        <div className="form-check">
                            <label className="form-check-label">
                            <input type="radio" className="form-check-input" name="proveedorEstado" id="abrirProveedor" value="0"/>
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
                                    <div className="col-6">
                                        <label>RUC del proveedor</label>
                                        <input type="number" className="form-control" name="rucProveedor" onChange={this.handleChangeRUC}/>
                                    </div>
                                    <div className="col-6">
                                        <label>Telefono</label>
                                        <input type="number" className="form-control" name="telefonoProveedor" id="telefonoProveedor" value={this.state.telefonoProveedor} onChange={this.handleChange}/>
                                    </div>

                                </div><br/>
                                <div className="row">
                                    <div className="col-12">
                                        <label>Nombre del proveedor</label>
                                        <input type="text" className="form-control" name="nombreProveedor" id="nombreProveedor" value={this.state.nombreProveedor} onChange={this.handleChange}/>
                                    </div>

                                </div><br/>
                                <div className="row">
                                    <div className="col-12">
                                        <label>Dirección</label>
                                        <input type="text" className="form-control" name="direccionProveedor" id="direccionProveedor" value={this.state.direccionProveedor} onChange={this.handleChange}/>
                                    </div>
                                </div><br/>
                                <div className="row">
                                    <div className="col-12">
                                        <label>Tipo</label>
                                        <input type="text" className="form-control" name="tipoProveedor" id="tipoProveedor" value={this.state.tipoProveedor} onChange={this.handleChange}/>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProveedor" onClick={()=>this.sendAgregarProveedor()}>
                                    Agregar</button>
                            </div>
                    </div>
                </div>
            </div>

        </Modal.Body>
      </Modal>

     <Modal

        show={this.state.estadoModalEditarProducto}
        onHide={() => this.cambiarModalEditarProducto()}
        >
        <Modal.Header closeButton>
          <Modal.Title id="example-custom-modal-styling-title">
             Editar Producto
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
            <div className="card card-default">
                <div className="modal-body">
                    <div className="card-body">
                            <div className="form-group" >
                                <div className="row">
                                    <div className="col-12">
                                        <label>Codigo</label>
                                        <input type="hidden" name="editarIdProducto" id="editarIdProducto" value={this.state.idProducto}/>
                                        <input type="text" className="form-control" name="codigoP" id="editarCodigoProductoNuevo" value={this.state.codigoP} onChange={this.handleChange}/>
                                    </div>
                                    <div className="col-12" id="editarAlertaCodigo">
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-6">
                                        <label>Marcas</label>
                                        <div className="input-group">
                                            <select className="form-control" name="idMarca" id="editarMarcaProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idMarca}>
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
                                            <select className="form-control" name="idTipo" id="editarTipoProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idTipo} >

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
                                        <input type="text" className="form-control" name="nombreP" id="editarNombreProductoNuevo" value={this.state.nombreP} onChange={this.handleChange} />
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-12">
                                        <label>Precio con IGV(18%)</label>
                                        <input type="text" name="precioP" id="editarPrecioVentaProducto" className="form-control"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value={this.state.precioP} data-type="currency" placeholder="S/1,000,000.00" onChange={this.handleChange} />
                                    </div>
                                </div>
                            </div>
                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="editarProducto" onClick={()=>{
                                    this.sendEditarProducto()
                                }}>
                                    Editar</button>
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
            onClose={() => this.cambiarToastGreen('','false')}
            show={this.state.estadoToastGreen}
            delay={3000}
            autohide
            >
              <Toast.Body>
                <h4>{this.state.messageGreen}</h4>
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
              onClose={() => this.cambiarToastRed('','false')}
              show={this.state.estadoToastRed}
              delay={3000}
              autohide

        >
          <Toast.Body>
            <h4>{this.state.messageRed}</h4>
          </Toast.Body>
      </Toast>

            </div>
    )
}}
export default Almacen
