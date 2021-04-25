import React from 'react'
import {Component} from 'react'
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'

class Proveedores extends Component{

    constructor(){
        super();
        this.state ={
            //editar proveedor
            proveedores_tb:[],
            editarRucProveedor:'',
            editarTelefonoProveedor:'',
            editarNombreProveedor:'',
            editarDireccionProveedor:'',
            editarIdProveedor:'',

            //agregar proveedor
            RUCdatos:[],
            aRuc:'',
            aTipo:'',
            aTelefono:'',
            aNombreProveedor:'',
            aDireccion:'',

            //modales
            estadoModalProveedor:false,
            estadoModalEditarProveedor: false,
            //toasts
            estadoToastRed:false,
            estadoToastGreen:false,
            messageRed:'',
            messageGreen:'',
            //botones
            estadoBoton:[],
            //query
            estadoQuery:''
        }

        this.handleChangeRUC = this.handleChangeRUC.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleChangeBuscador= this.handleChangeBuscador.bind(this);

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
        cambiarModalProveedor(){
            this.setState({
                estadoModalProveedor: !this.state.estadoModalProveedor
            })
        }
        cambiarModalEditarProveedor(){
            this.setState({
                estadoModalEditarProveedor: !this.state.estadoModalEditarProveedor
            })
        }


    // Paginate y buscadores

        agregarBotones(){
          let z = this.state.proveedores_tb.last_page
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
          fetch(`/almacen/proveedores/tb_proveedores?page=${number}&buscar=${this.state.estadoQuery}`)
            .then(res => res.json())
            .then(data => {
                this.setState({proveedores_tb: data},()=>{
                  console.log(this.state.proveedores_tb)
                  this.agregarBotones();
                });

          })

        }


        fetchQuery(query){
            fetch(`/almacen/proveedores/tb_proveedores?buscar=${query}`)
              .then(res => res.json())
              .then(data => {
                this.setState({proveedores_tb: data,estadoQuery: query}, () => {
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

//------------|

    fetchDataTable(){
        fetch('/almacen/proveedores/tb_proveedores')
          .then(res => res.json())
          .then(data => {
              this.setState({proveedores_tb: data},()=>{
                console.log(this.state.proveedores_tb)
                this.agregarBotones();
              });

      })
    }
    fetchRUC(){
        fetch(`/consult/ruc/${this.state.aRuc}`,
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
        ).then(res => res.json())
            .then(data => {
            this.setState({RUCdatos: data}, () => {
                this.datosdeRUC();
                });
              })
        }

    datosdeRUC(){

            this.state.RUCdatos ?
                     this.setState({
                         aTelefono:this.state.RUCdatos.persona.telefonos[0],
                         aNombreProveedor:this.state.RUCdatos.persona.razonSocial,
                         aDireccion:this.state.RUCdatos.persona.direccion,
                         aTipo:this.state.RUCdatos.persona.tipo
                    },console.log(this.state.RUCdatos)) :null
        }


    sendAgregarProveedor(){
            fetch('/almacen/proveedor/crear',
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token': csrf_token,
                        rucProveedor:this.state.aRuc,
                        telefonoProveedor:this.state.aTelefono,
                        nombreProveedor:this.state.aNombreProveedor,
                        direccionProveedor:this.state.aDireccion,
                        tipoProveedor:this.state.aTipo
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
                  console.log("correcto creado Proveedor:");
                  this.cambiarToastGreen("el Proveedor");
                  this.fetchDataTable();

                }
                else{
                  console.log("error");
                  this.cambiarToastRed("en agregar Proveedor");
                }
            })
            .catch((error)=> {
              console.log('Hubo un problema con la petición Fetch:' + error.message);
              this.cambiarToastRed("en agregar Proveedor");
            });
     }
     sendEditarProveedor(){
             fetch('/almacen/proveedores/editar',
                 {
                     method: 'POST',
                     body: JSON.stringify({
                         '_token': csrf_token,
                         editarRucProveedor:this.state.editarRucProveedor,
                         editarTelefonoProveedor:this.state.editarTelefonoProveedor,
                         editarNombreProveedor:this.state.editarNombreProveedor,
                         editarDireccionProveedor:this.state.editarDireccionProveedor,
                         editarIdProveedor:this.state.editarIdProveedor
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
                 console.log("correcto editado Proveedor:");
                 this.cambiarToastGreen("el proveedor");
                 this.fetchDataTable();

               }
               else{
                 console.log("error");
                 this.cambiarToastRed("en editar Proveedor");
               }
           })
           .catch((error)=> {
             console.log('Hubo un problema con la petición Fetch:' + error.message);
             this.cambiarToastRed("en editar Proveedor");
           });
      }


    editTask(ruc,nombreProveedor,telf,direccion,id){
      let telefono;
      if(telf == null){
        telefono = '';
      }
        this.setState({
            editarRucProveedor: ruc,
            editarNombreProveedor: nombreProveedor,
            editarTelefonoProveedor: telefono,
            editarDireccionProveedor: direccion,
            editarIdProveedor: id
        })
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
                          <button type="button" onClick={()=>this.cambiarModalProveedor()} className="btn waves-effect waves-light btn-warning">Agregar Proveedor</button>
                      </div>
                  </div>
              </div>


              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <table id="tb_proveedores" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th><h4 className="card-title">Registro de proveedores</h4></th>
                                      <th><input type="text" onChange={this.handleChangeBuscador} /></th>
                                  </tr>
                                  <tr>
                                      <th>RUC proveedor</th>
                                      <th>Proveedor</th>
                                      <th>Telefóno</th>
                                      <th>Dirección</th>
                                      <th>Tipo</th>
                                      <th>Opciones</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  {
                                      this.state.proveedores_tb.data ?
                                      this.state.proveedores_tb.data.map(task =>{
                                          return (
                                              <tr key={task.id}>
                                                  <td>{task.ruc}</td>
                                                  <td>{task.nombre}</td>
                                                  <td>{task.numero}</td>
                                                  <td>{task.direccion}</td>
                                                  <td>{task.tipo}</td>
                                                  <td><button onClick={()=>{this.editTask(task.ruc,task.nombre,task.numero,task.direccion,task.id);
                                                    this.cambiarModalEditarProveedor()}}
                                                  className="btn btn-sm btn-secondary editar" type="button"><i className="mdi mdi-lead-pencil"></i></button></td>
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
                  show={this.state.estadoModalProveedor}
                  onHide={() => this.cambiarModalProveedor()}
            >
              <Modal.Header closeButton>
                    <Modal.Title id="example-custom-modal-styling-title">
                      Agregar Proveedor
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
                                              <input type="number" className="form-control" name="aRuc" id="rucProveedor" onChange={this.handleChangeRUC}/>
                                          </div>
                                          <div className="col-6">
                                              <label>Telefono</label>
                                              <input type="number" className="form-control" name="aTelefono" id="telefonoProveedor" value={this.state.aTelefono} onChange={this.handleChange}/>
                                          </div>

                                      </div><br/>
                                      <div className="row">
                                          <div className="col-12">
                                              <label>Nombre del proveedor</label>
                                              <input type="text" className="form-control" name="aNombreProveedor" id="nombreProveedor" value={this.state.aNombreProveedor} onChange={this.handleChange}/>
                                          </div>

                                      </div><br/>
                                      <div className="row">
                                          <div className="col-12">
                                              <label>Dirección</label>
                                              <input type="text" className="form-control" name="aDireccion" id="direccionProveedor" value={this.state.aDireccion} onChange={this.handleChange}/>
                                          </div>
                                      </div><br/>
                                      <div className="row">
                                          <div className="col-12">
                                              <label>Tipo</label>
                                              <input type="text" className="form-control" name="aTipo" id="tipoProveedor" value={this.state.aTipo} onChange={this.handleChange}/>
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
                  show={this.state.estadoModalEditarProveedor}
                  onHide={() => this.cambiarModalEditarProveedor()}
            >
              <Modal.Header closeButton>
                    <Modal.Title id="example-custom-modal-styling-title">
                      Editar Proveedor
                    </Modal.Title>
              </Modal.Header>
              <Modal.Body>
                    <div className="card card-default">
                        <div className="card-header cabezera">
                          </div>
                            <div className="modal-body">
                              <div className="card-body">
                                    <div className="form-group" >
                                        <div className="row">
                                            <div className="col-6">
                                                <label>RUC:</label>
                                                <input type="hidden" name="editarIdProveedor" id="editarIdProveedor" />
                                                <input type="text" className="form-control" name="editarRucProveedor" id="editarRucProveedor" value={this.state.editarRucProveedor} onChange={this.handleChange}/>
                                            </div>
                                            <div className="col-6">
                                                <label>Telefóno:</label>
                                                <input type="text" className="form-control" name="editarTelefonoProveedor" id="editarTelefonoProveedor" value={this.state.editarTelefonoProveedor} onChange={this.handleChange}/>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Nombre del proveedor:</label>
                                                <textarea className="form-control" name="editarNombreProveedor" id="editarNombreProveedor" value={this.state.editarNombreProveedor} onChange={this.handleChange}></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Dirección:</label>
                                                <textarea className="form-control" name="editarDireccionProveedor" id="editarDireccionProveedor" value={this.state.editarDireccionProveedor} onChange={this.handleChange} ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group boton">
                                        <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="editarProveedor" onClick={()=>this.sendEditarProveedor()}>
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

    )
}}

export default Proveedores
