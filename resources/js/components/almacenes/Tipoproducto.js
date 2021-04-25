import React from 'react'
import {Component} from 'react'
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'

class Tipoproducto extends Component{

    constructor(){
        super();
        this.state ={
          tiposProductos_tb:[],
          nombreTipoProducto: '',
          editarNombreTipoProducto:'',
          editarIdTipoProducto:'',

          //modales
          estadoModalTipoProducto:false,
          estadoModalEditarTipoProducto:false,
          //Toast
          estadoToastRed:false,
          estadoToastGreen:false,
          messageRed:'',
          messageGreen:'',
          //botones
          estadoBoton:[],
          //query
          estadoQuery:''


        }
       this.handleChange = this.handleChange.bind(this);
       this.agregarBotones = this.agregarBotones.bind(this);
       this.handleChangeBuscador = this.handleChangeBuscador.bind(this);
    }



    //modales y toasts

        cambiarToastRed(message) {
            this.setState({
                messageRed: message,
                estadoToastRed: !this.state.estadoToastRed
            });
        }
        cambiarToastGreen(message) {
            this.setState({
                messageGreen: message,
                estadoToastGreen: !this.state.estadoToastGreen
            });
        }
        cambiarModalTipoProducto(){
            this.setState({
                estadoModalTipoProducto: !this.state.estadoModalTipoProducto
            })
        }
        cambiarModalEditarTipoProducto(nombre,id){
            this.setState({
                estadoModalEditarTipoProducto: !this.state.estadoModalEditarTipoProducto,
                editarNombreTipoProducto: nombre,
                editarIdTipoProducto: id
            })
        }



    // Paginate y buscadores

        agregarBotones(){
          let z = this.state.tiposProductos_tb.last_page
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
          fetch(`/almacen/tiposproductos/tb_tiposProductos?page=${number}&bnombre=${this.state.estadoQuery}`)
            .then(res => res.json())
            .then(data => {
                this.setState({tiposProductos_tb: data},()=>{
                  console.log(this.state.tiposProductos_tb)
                  this.agregarBotones();
                });

          })

        }


        fetchQuery(query){
            fetch(`/almacen/tiposproductos/tb_tiposProductos?bnombre=${query}`)
              .then(res => res.json())
              .then(data => {
                this.setState({tiposProductos_tb: data,estadoQuery: query}, () => {
                    this.agregarBotones();
                    console.log("obtenido?");
                });
              })
        }

        handleChangeBuscador (e){
          const {name , value} = e.target;
          console.log(value);
          this.fetchQuery(value);
          console.log(name);

        }

//-------------------------------------------------------|

    fetchDataTable(){
      fetch('/almacen/tiposproductos/tb_tiposProductos')
        .then(res => res.json())
        .then(data => {
            this.setState({tiposProductos_tb: data},()=>{
              console.log(this.state.tiposProductos_tb)
              this.agregarBotones();
            });
        })
     }
     sendAgregarTipoProducto(){

             fetch(`/almacen/tipo/crear`,
             {
                 method: 'POST',
                 body: JSON.stringify({
                     '_token': csrf_token,
                     nuevoTipoProducto: this.state.nombreTipoProducto
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
               this.cambiarToastGreen("el tipo de producto");
               this.fetchDataTable();
             }
             else{
               console.log("error");
               this.cambiarToastRed("en agregar tipo de producto");
             }

         })
         .catch((error)=> {
           console.log('Hubo un problema con la petición Fetch:' + error.message);
           this.cambiarToastRed("en agregar tipo de producto");
         });  }

     sendEditarTipoProducto(){

                 fetch(`/almacen/tiposproductos/editar`,
                 {
                     method: 'POST',
                     body: JSON.stringify({
                         '_token': csrf_token,
                         editarNombreTipoProducto: this.state.editarNombreTipoProducto,
                         editarIdTipoProducto: this.state.editarIdTipoProducto
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
                   this.cambiarToastGreen("el tipo de producto");
                   this.fetchDataTable();
                 }
                 else{
                   console.log("error");
                   this.cambiarToastRed("en editar tipo de producto");
                 }

             })
             .catch((error)=> {
               console.log('Hubo un problema con la petición Fetch:' + error.message);
               this.cambiarToastRed("en editar el tipo de producto");
             });  }

     deleteTipoProducto(id){
          fetch(`/almacen/tiposproductos/eliminar`,{
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
                       this.cambiarToastGreen("en eliminar tipo de producto");
                       this.fetchDataTable();
                     }
                     else{
                       console.log("error");
                       this.cambiarToastRed("en eliminar tipo de producto");
                     }
                 })
                 .catch((error)=> {
                   console.log('Hubo un problema con la petición Fetch:' + error.message);
                   this.cambiarToastRed("en eliminar tipo de producto");
                 });

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

    render(){
        return(


                    <div className="row">

                        <div className="col-lg-12 grid-margin stretch-card">
                          <div className="card">
                              <div className="card-body">
                                  <h4 className="card-title">Modulos de agregar</h4>
                                  <button type="button" className="btn waves-effect waves-light btn-success" onClick={()=>this.cambiarModalTipoProducto()}>Agregar Tipo de Producto</button>
                              </div>
                          </div>
                      </div>


                      <div className="col-lg-12 grid-margin stretch-card">
                          <div className="card">
                              <div className="card-body">
                                  <h4 className="card-title">Tipos de productos</h4>
                                  <table id="tb_tiposProductos" className="table table-striped" style={{width:'100%'}}>
                                      <thead>
                                          <tr>
                                            <th style={{textAlign:'right'}}><input type="text" onChange={this.handleChangeBuscador} /></th>
                                          </tr>
                                          <tr>
                                              <th>Tipo de producto</th>
                                              <th>Opciones</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          {
                                          this.state.tiposProductos_tb.data ?
                                          this.state.tiposProductos_tb.data.map(task =>{
                                              return (
                                                  <tr key={task.id}>
                                                      <td>{task.nombre}</td>
                                                      <td><button onClick={()=>this.cambiarModalEditarTipoProducto(task.nombre,task.id)}
                                                      className="btn btn-sm btn-secondary editar" type="button"><i className="mdi mdi-lead-pencil"></i></button>
                                                      <button className="btn btn-sm btn-danger eliminar" type="button" onClick={()=>this.deleteTipoProducto(task.id)}><i className="mdi mdi-delete"></i></button>
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

                      <Modal

                          show={this.state.estadoModalTipoProducto}
                          onHide={() => this.cambiarModalTipoProducto()}
                          >
                          <Modal.Header closeButton>
                            <Modal.Title id="example-custom-modal-styling-title">
                                <h4> Agregar tipo de producto </h4>
                            </Modal.Title>
                          </Modal.Header>
                          <Modal.Body>
                                <div className="card card-default">
                                    <div className="modal-body">
                                        <div className="card-body">
                                                <div className="form-group">
                                                    <div className="row">
                                                        <div className="col-12">
                                                            <label>Nombre del tipo de producto</label>
                                                            <input type="text" className="form-control" name="nombreTipoProducto" id="nuevoTipoProducto"  value={this.state.nombreTipoProducto} onChange={this.handleChange}/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="form-group boton">
                                                    <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearTipoProducto" onClick={()=>this.sendAgregarTipoProducto()}>
                                                        CREAR</button>
                                                </div>  
                                        </div>
                                    </div>
                                </div>
                          </Modal.Body>
                        </Modal>

                      <Modal
                          show={this.state.estadoModalEditarTipoProducto}
                          onHide={() => this.cambiarModalEditarTipoProducto()}
                          >
                          <Modal.Header closeButton>
                            <Modal.Title id="example-custom-modal-styling-title">
                              <h4> Editar tipo de producto </h4>
                            </Modal.Title>
                          </Modal.Header>
                          <Modal.Body>
                              <div className="card card-default">
                                  <div className="modal-body">
                                      <div className="card-body">
                                              <div className="form-group">
                                                  <div className="row">
                                                      <div className="col-12">
                                                          <label>Nombre del tipo de producto</label>
                                                          <input type="text" className="form-control" name="editarNombreTipoProducto" id="editarNombreTipoProducto" value={this.state.editarNombreTipoProducto} onChange={this.handleChange}/>
                                                          <input type="hidden" name="editarIdTipoProducto" id="editarIdTipoProducto" />
                                                      </div>
                                                  </div>
                                              </div>
                                              <div className="form-group boton">
                                                  <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="editarTipoProducto" onClick={()=>this.sendEditarTipoProducto()}>
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

        )}}

export default Tipoproducto
