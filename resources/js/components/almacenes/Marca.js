import React from 'react'
import {Component} from 'react'
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'

class Marca   extends Component {

    constructor(){
      super();
      this.state ={

        marca_tb :[],
        nombreMarca:'',
        editarNombreMarca:'',
        editarIdMarca:'',
        //modales
        estadoModalMarca:false,
        estadoModalEditarMarca:false,
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
      this.agregarBotones = this.agregarBotones.bind(this);
      this.fetchDataTable = this.fetchDataTable.bind(this);
      this.handleChange = this.handleChange.bind(this);
      this.handleChangeBuscador = this.handleChangeBuscador.bind(this);
  }


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

      cambiarModalMarca(){
          this.setState({
              estadoModalMarca: !this.state.estadoModalMarca
          })
      }
      cambiarModalEditarMarca(id,nombre){
          this.setState({
              estadoModalEditarMarca: !this.state.estadoModalEditarMarca,
              editarNombreMarca: nombre,
              editarIdMarca: id
          })
      }
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
                this.cambiarToastGreen("la marca");
                this.fetchDataTable();
              }
              else{
                console.log("error");
                this.cambiarToastRed("en agregar Marca");
              }

          })
          .catch((error)=> {
            console.log('Hubo un problema con la petición Fetch:' + error.message);
            this.cambiarToastRed("en agregar Marca");
          });  }

      sendEditarMarca(){

                  fetch(`/almacen/marcas/editar`,
                  {
                      method: 'POST',
                      body: JSON.stringify({
                          '_token': csrf_token,
                          editarNombreMarca: this.state.editarNombreMarca,
                          editarIdMarca: this.state.editarIdMarca
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
                    this.cambiarToastGreen("la marca");
                    this.fetchDataTable();
                  }
                  else{
                    console.log("error");
                    this.cambiarToastRed("en editar Marca");
                  }

              })
              .catch((error)=> {
                console.log('Hubo un problema con la petición Fetch:' + error.message);
                this.cambiarToastRed("en editar Marca");
              });  }

      deleteMarca(id){
            console.log(id);
                fetch(`/almacen/marcas/eliminar`,{
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
                      this.cambiarToastGreen("en eliminar Marca");
                      this.fetchDataTable();
                    }
                    else{
                      console.log("error");
                      this.cambiarToastRed("en eliminar Marca");
                    }
                })
                .catch((error)=> {
                  console.log('Hubo un problema con la petición Fetch:' + error.message);
                  this.cambiarToastRed("en eliminar Marca");
                });

      }

    agregarBotones(){
      let z = this.state.marca_tb.last_page
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
      fetch(`/almacen/marcas/tb_marcas?page=${number}&bnombre=${this.state.estadoQuery}`)
        .then(res => res.json())
        .then(data => {
            this.setState({marca_tb: data},()=>{
              console.log(this.state.marca_tb)
              this.agregarBotones();
            });

      })

    }


    fetchDataTable(){
      fetch('/almacen/marcas/tb_marcas')
        .then(res => res.json())
        .then(data => {
            this.setState({marca_tb: data},()=>{
              console.log(this.state.marca_tb)
              this.agregarBotones();
            });

      })
    }
    fetchQuery(query){
        fetch(`/almacen/marcas/tb_marcas?bnombre=${query}`)
          .then(res => res.json())
          .then(data => {
            this.setState({marca_tb: data,estadoQuery: query}, () => {
                this.agregarBotones();
                console.log("obtenido?");
            });
          })
    }

    handleChange (e){
      const {name , value} = e.target;
      this.setState({
          [name] : value

      })
      console.log(name);
      console.log(value);
    }
    handleChangeBuscador (e){
      const {name , value} = e.target;
      console.log(value);
      this.fetchQuery(value);
      console.log(name);

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
                          <button type="button" className="btn waves-effect waves-light btn-info" onClick={()=>this.cambiarModalMarca()}>Agregar Marca</button>
                      </div>
                  </div>
              </div>


              <div className="col-lg-12 grid-margin stretch-card">
                    <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Marcas</h4>
                          <table id="tb_marcas" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                    <th style={{textAlign:'right'}}><input type="text" onChange={this.handleChangeBuscador} /></th>
                                  </tr>
                                  <tr>
                                      <th>Nombre de la marca</th>
                                      <th>Opciones</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  {
                                          this.state.marca_tb.data ?
                                          this.state.marca_tb.data.map(task =>{
                                              return (
                                                  <tr key={task.id}>
                                                      <td>{task.nombre}</td>
                                                      <td><button
                                                      className="btn btn-sm btn-secondary editar" type="button" onClick={()=>this.cambiarModalEditarMarca(task.id,task.nombre)}><i className="mdi mdi-lead-pencil"></i></button>
                                                      <button className="btn btn-sm btn-danger eliminar" type="button" onClick={()=>this.deleteMarca(task.id)}><i className="mdi mdi-delete"></i></button>
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

                show={this.state.estadoModalEditarMarca}
                onHide={() => this.cambiarModalEditarMarca()}
                >
                <Modal.Header closeButton>
                  <Modal.Title id="example-custom-modal-styling-title">
                    Editar Marca
                  </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                      <div className="card card-default">
                          <div className="modal-body">
                              <div className="card-body">
                                      <div className="form-group">
                                          <div className="row">
                                              <div className="col-12">
                                                  <label>Nombre de la marca</label>
                                                  <input type="text" className="form-control" name="editarNombreMarca" id="editarNombreMarca"  value={this.state.editarNombreMarca} onChange={this.handleChange}/>
                                                  <input type="hidden" name="editarIdMarca" id="editarIdMarca" />
                                              </div>
                                          </div>
                                      </div>
                                      <div className="form-group boton">
                                          <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="editarMarca" onClick={()=>this.sendEditarMarca()}>
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

export default Marca
