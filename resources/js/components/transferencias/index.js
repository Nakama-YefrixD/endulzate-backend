import React, { Component } from 'react'
import Modal from 'react-bootstrap/Modal'
import ModalDeclararTransferencia from './modalDeclararTransferencia/index'
import TB_Transferencias from './tabla/tb_transferencias'
import Buscadores_tb_transferencias from './tabla/buscadores_tb_transferencias'
import agregarBotones from '../extras/agregarBotones'

class indexTransferencias extends Component {
    constructor(){
        super();
        this.state ={
            mostrarModalDeclararTransferencia   : false,
            sucursalesUsuario                   : [],
            sucursales                          : [],


            tb_transferencias  : [],
            numeroPagina       : '',
            estadoBoton        : []

        }

        this.mostrarModalDeclararTransferencia = this.mostrarModalDeclararTransferencia.bind(this);
        
        this.fetchSucursalesUsuario = this.fetchSucursalesUsuario.bind(this);
        this.fetchSucursales        = this.fetchSucursales.bind(this);
        this.fetchTransferencias    = this.fetchTransferencias.bind(this);
    }

    mostrarModalDeclararTransferencia(){
        this.setState({
            mostrarModalDeclararTransferencia : !this.state.mostrarModalDeclararTransferencia
        })
    }
    
    componentDidMount(){
        this.fetchSucursalesUsuario();
        this.fetchSucursales();
        this.fetchTransferencias(1, '', '');
    }

    fetchSucursalesUsuario()
    {
        fetch('/consultar/sucursales/usuario')
                .then(
                    res => res.json()
                )
                .then(
                    data => {
                        this.setState({
                            sucursalesUsuario: data.sucursales
                        });
                        
        
                    }
                )
                
    }

    fetchSucursales()
    {
        fetch('/consultar/sucursales')
                .then(
                    res => res.json()
                )
                .then(
                    data => {
                        this.setState({
                            sucursales: data.sucursales
                        });

                    }
                )
    }

    fetchTransferencias(number, cliente, numeroComprobante){
        let url = `/almacen/transferencias/tb_transferencias?page=${number}&idSucursal=`;
        fetch(
            url
        )
        .then(
            res => res.json()
        )
        .then(
            data => {

                this.setState({
                    tb_transferencias   : data['tb_transferencias'].data,
                    numeroPagina        : number
                },()=>{
                    
                    this.setState({
                        estadoBoton : agregarBotones(data['tb_transferencias'].last_page)
                    })
                });
            }
        )
    }
   


    render(){
        return(
            <div className="row">
               <div className="col-lg-12 grid-margin stretch-card">
                    <div className="card">
                        <div className="card-body">
                            <h4 className="card-title">Modulos de agregar</h4>
                            <div className="button-group">
                                <button 
                                    type="button" 
                                    className="btn waves-effect waves-light btn-primary"
                                    onClick={()=>this.mostrarModalDeclararTransferencia()}
                                    >Declarar Transferencia</button>
                            </div>
                        </div>
                    </div>
                </div>

                <Buscadores_tb_transferencias 
                                    
                />

                <TB_Transferencias 
                    fetchTransferencias = {this.fetchTransferencias}
                    tb_transferencias   = {this.state.tb_transferencias}
                    estadoBoton         = {this.state.estadoBoton}
                />
                

                <Modal
                    size="lg"
                    show    = {this.state.mostrarModalDeclararTransferencia}
                    onHide  = {() => this.mostrarModalDeclararTransferencia()}
                >
                    <Modal.Header closeButton>
                        <Modal.Title id="example-custom-modal-styling-title">
                            Declarar Transferencia
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <ModalDeclararTransferencia
                            mostrarModalDeclararTransferencia = {this.mostrarModalDeclararTransferencia}

                            fetchSucursalesUsuario  = {this.fetchSucursalesUsuario}
                            fetchSucursales         = {this.fetchSucursales}

                            sucursalesUsuario   = {this.state.sucursalesUsuario}
                            sucursales          = {this.state.sucursales}

                        />
                    </Modal.Body>
                </Modal>
            </div>

        )
    }
}

export default indexTransferencias
