import React from 'react'
import {Component} from 'react';
import EntradaCrear from  './EntradaCrear';
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'

class Entrada extends Component {
    constructor(){
        super();
        this.state ={

            entrada_tb :[],
            addContainer: false,

            //modales
            estadoModalEntrada:false,
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
        this.fechaEntradaDataTabla=this.fetchEntradaDataTabla.bind(this);
        this.add = this.add.bind(this);
        this.handleChangeBuscador = this.handleChangeBuscador.bind(this);
    }

    componentDidMount(){
      this.fetchEntradaDataTabla();
    }

    add() {
      this.setState({addContainer : !this.state.addContainer})
    }

//envios posts

    sendAgregarEntrada(){

            fetch(`/almacen/entrada/crear`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    nombreEntrada: this.state.nombreEntrada
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
              this.cambiarToastGreen("la entrada");
            }
            else{
              console.log("error");
              this.cambiarToastRed("en agregar Entrada");
            }

        })
        .catch((error)=> {
          console.log('Hubo un problema con la petición Fetch:' + error.message);
          this.cambiarToastRed("en agregar Entrada");
        });  }


  eliminarEntrada(id,codigo,cantidad){
            fetch(`/almacen/entrada/eliminar`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    codigo: codigo,
                    id: id,
                    cantidad: cantidad
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
              console.log("eliminado");
              this.cambiarToastGreen("Se elimino la entrada");
              this.fetchEntradaDataTabla();
            }
            else{
              console.log("error");
              this.cambiarToastRed("Error al eliminar Entrada");
            }

        })
        .catch((error)=> {
          console.log('Hubo un problema con la petición Fetch:' + error.message);
          this.cambiarToastRed("Error en eliminar entrada");
        });  }



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
    cambiarModalEntrada(){
       console.log("holaaaaaa");
        this.setState({
            estadoModalEntrada: !this.state.estadoModalEntrada
        })
    }


// Paginate y buscadores

    agregarBotones(){
      let z = this.state.entrada_tb.last_page
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
      fetch(`/almacen/entrada/tb_entradas?page=${number}&buscar=${this.state.estadoQuery}`)
        .then(res => res.json())
        .then(data => {
            this.setState({entrada_tb: data},()=>{
              console.log(this.state.entrada_tb)
              this.agregarBotones();
            });

      })

    }

    fetchQuery(query){
        fetch(`/almacen/entrada/tb_entradas?buscar=${query}`)
          .then(res => res.json())
          .then(data => {
            this.setState({entrada_tb: data,estadoQuery: query}, () => {
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
//|-------------------------------------------|
    handleChange (e){
      const {name , value} = e.target;
      this.setState({
          [name] : value

      })
      console.log(name);
      console.log(value);
    }

    fetchEntradaDataTabla(){
      fetch('/almacen/entrada/tb_entradas')
          .then(res => res.json())
          .then(data => {
              this.setState({entrada_tb: data},()=>{
                console.log(this.state.entrada_tb)
                this.agregarBotones();
              });

      })
    }




    render(){
        return(

            <div className="row">
              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Modulos de agregar</h4>
                          <button type="button" className="btn waves-effect waves-light btn-primary" onClick={() => this.add()}>Agregar Entrada</button>
                      </div>
                  </div>
              </div>
               { this.state.addContainer && <EntradaCrear addContainer={this.add} />}

              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">

                          <table id="tb_entradas" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th><h4 className="card-title">Registro de entradas</h4></th>
                                      <th><input type="text" onChange={this.handleChangeBuscador} /></th>
                                  </tr>
                                  <tr>
                                      <th>Numero Factura</th>
                                      <th>Fecha</th>
                                      <th>Codigo producto</th>
                                      <th>Nombre producto</th>
                                      <th>Cantidad</th>
                                      <th>Precio</th>
                                      <th>Opciones</th>
                                  </tr>
                                </thead>
                                <tbody>
                                     {
                                      this.state.entrada_tb.data ?
                                      this.state.entrada_tb.data.map(task =>{
                                          return (
                                              <tr key={task.id}>
                                                  <td>{task.factura}</td>
                                                  <td>{task.fecha}</td>
                                                  <td>{task.codigo}</td>
                                                  <td>{task.nombre}</td>
                                                  <td>{task.cantidad}</td>
                                                  <td>{task.precio}</td>
                                                  <th>
                                                  <button className="btn btn-sm btn-primary ver" type="button" onClick={()=>this.eliminarEntrada(task.entradaId,task.codigo,task.cantidad)}><i className="mdi mdi-delete"></i></button>
                                                  </th>
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
                  size="lg"
                  show={this.state.estadoModalEntrada}
                  onHide={() => this.cambiarModalEntrada()}
                  >
                  <Modal.Header closeButton>
                    <Modal.Title id="example-custom-modal-styling-title">
                      Detalles de la entrada
                    </Modal.Title>
                  </Modal.Header>
                  <Modal.Body>
                              <div className="card card-default">
                                      <div className="modal-body">
                                          <div className="card-body" id="entradaDetalladaModalBody">
                                          <label>Proveedor:</label><br/>
                                            <span id="proveedorEntradaDetalle"></span><br/>
                                          <label>Numero entrada:</label><br/>
                                            <span id="numeroEntradaDetalle"></span><br/>
                                          <label>Fecha de emisón:</label><br/>
                                            <span id="fechaEntradaDetalle"></span><br/><br/>
                                          <table className="table table-bordered" id="tablaDetallesEntradaModal">
                                            <thead>
                                              <tr>
                                                <th>
                                                  #
                                                </th>
                                                <th>
                                                  Producto
                                                </th>
                                                <th>
                                                  Precio
                                                </th>
                                                <th>
                                                  Cantidad
                                                </th>
                                                <th> Importe </th>
                                              </tr>
                                            </thead>
                                          <tbody>

                                          </tbody>
                                        </table>
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
                        <h5>{this.state.messageGreen}</h5>
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
                    <h5>{this.state.messageRed}</h5>
                  </Toast.Body>
              </Toast>


            </div>

)
}}
export default Entrada
