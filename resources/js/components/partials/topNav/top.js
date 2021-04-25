import React, { Component } from 'react'
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'
import CerrarCajaVentaComponente from './cerrarCajaVenta'
import { Link } from 'react-router-dom'
import cogoToast from 'cogo-toast';


class Top extends Component {

    constructor(){
        super();
        this.state ={
            // variables de la apertura de la caja
            idCajaVenta             : '',
            sucursalNombre          : '',
            usuarioNombre           : '',
            totalApertura           : '',
            totalAperturo           : '',
            observacionesApertura   : '',
            totalCierre             : '',

            registrarGastoIngreso   : '',
            motivoGastoIngreso      : '',

            // Estado de la caja
            estadoCajaVenta              : true,  // false = NO SE SABE EL ESTADO DE LA CAJA DE VENTA, TRUE = SI SABEMOS
            cerrarAbrirCajaVenta         : false, // false = ABRIR CAJARA, TRUE = CERRAR CAJA
            txt_btn_abriCerrarCaja       : 'ABRIR CAJA',

            //modal
            estadoModalAbrirCajaVenta    : false,
            estadoModalCerrarCajaVenta   : false,
            estadoModalRegistrarGasto    : false,
            estadoModalRegistrarIngreso  : false,

            // 

            // TOAST
            estadoToast         : false,
            mensajeToast        : '',
            colorToastDanger    : 'rgba(205,55,55,0.7)',
            colorToastSuccess   : 'rgba(76,208,76,0.7)',
            colorToast          : '',

            // LOADING
            loadModalCierreCajaVenta    : true,
        }
        this.modalAbrirCajaVenta    = this.modalAbrirCajaVenta.bind(this);
        this.modalCerrarCajaVenta   = this.modalCerrarCajaVenta.bind(this);
        this.getEstadoCajaVent      = this.getEstadoCajaVent.bind(this);
        this.abrirCerrarCajaVenta   = this.abrirCerrarCajaVenta.bind(this);
        this.getCambioTotalAperturo = this.getCambioTotalAperturo.bind(this);
        this.getCambioObservacion   = this.getCambioObservacion.bind(this);
        this.sendAbrirCaja          = this.sendAbrirCaja.bind(this);
        this.sendRegistrarGasto     = this.sendRegistrarGasto.bind(this);
        this.modalRegistrarGasto    = this.modalRegistrarGasto.bind(this);
        this.getCambioGastoIngreso          = this.getCambioGastoIngreso.bind(this);
        this.getCambioMotivoGastoIngreso    = this.getCambioMotivoGastoIngreso.bind(this);
        this.sendRegistrarIngreso           = this.sendRegistrarIngreso.bind(this);
        this.modalRegistrarIngreso          = this.modalRegistrarIngreso.bind(this);
        this.activarToast                   = this.activarToast.bind(this);
        this.getTotalCierreCajaVenta        = this.getTotalCierreCajaVenta.bind(this);

    }
    componentDidMount(){
        this.getEstadoCajaVent()

    }

    getEstadoCajaVent(){
        fetch(`/caja/venta/estado`,
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
        .then(response => response.json())
        .then(data => {
            
            console.log(data)
            let texto;
            let contenedor  = document.querySelector("#btn_abrirCerrarCaja");
            
            if(data['respuesta'] == true){
                contenedor.classList.remove("btn-primary");
                contenedor.classList.add("btn-danger");
                texto = "CERRAR CAJA"

                let cajaVenta   = data['cajaVenta'];
                this.setState({
                    idCajaVenta             : cajaVenta['idCajaVenta'],
                    totalAperturo           : cajaVenta['totalAperturo'],
                    totalCierre             : cajaVenta['totalCierre'],
                })

            }else{
                contenedor.classList.remove("btn-danger");
                contenedor.classList.add("btn-primary");
                texto = "ABRIR CAJA";
                console.log("abrirrrr caja")

            }
            
            this.setState({
                cerrarAbrirCajaVenta    : data['respuesta'],
                usuarioNombre           : data['nombreUsuario'],
                sucursalNombre          : data['sucursalNombre'],
                totalApertura           : data['totalApertura'],

                estadoCajaVenta         : true,
                txt_btn_abriCerrarCaja  : texto,

                loadModalCierreCajaVenta    : false
            });
            
        })
    }

    getTotalCierreCajaVenta(){
        fetch(`/caja/venta/estado/totalcierre`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token'        : csrf_token,
                    'idCajaVenta'   : this.state.idCajaVenta
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',
                }
            }
        )
        .then(response => response.json())
        .then(data => {
            
            
            this.setState({
                
                totalCierre               : data['totalCierre'],
                loadModalCierreCajaVenta  : false
            });
            
        })
    }

    abrirCerrarCajaVenta(){
        console.log(this.state.cerrarAbrirCajaVenta)
        if(this.state.cerrarAbrirCajaVenta){
            this.modalCerrarCajaVenta();
        }else{
            this.modalAbrirCajaVenta()
        }

    }

    modalAbrirCajaVenta() {
        let texto;
        let contenedor = document.querySelector("#btn_abrirCerrarCaja");
                
        if(this.state.estadoModalAbrirCajaVenta){
            contenedor.classList.remove("btn-info");
            contenedor.classList.add("btn-primary");
            texto = "ABRIR CAJA"

        }else{
            contenedor.classList.remove("btn-primary");
            contenedor.classList.add("btn-info");
            texto = "CONFIGURANDO CAJA"
            
        }

        this.setState({
            estadoModalAbrirCajaVenta  : !this.state.estadoModalAbrirCajaVenta,
            txt_btn_abriCerrarCaja      : texto
        });
        
    }

    modalCerrarCajaVenta() {
        this.getTotalCierreCajaVenta();
        let texto;
        let contenedor = document.querySelector("#btn_abrirCerrarCaja");
                
        if(this.state.estadoModalCerrarCajaVenta){
            contenedor.classList.remove("btn-info");
            contenedor.classList.add("btn-danger");
            texto = "CERRAR CAJA"

        }else{
            contenedor.classList.remove("btn-danger");
            contenedor.classList.add("btn-info");
            texto = "VISUALIZANDO CAJA"
            
        }

        this.setState({
            estadoModalCerrarCajaVenta  : !this.state.estadoModalCerrarCajaVenta,
            txt_btn_abriCerrarCaja      : texto,

            loadModalCierreCajaVenta    : true
        });
        
    }

    getCambioTotalAperturo(e){
        const {name , value} = e.target;
        
        this.setState({
            totalAperturo  : value
        })
    }

    getCambioObservacion(e){
        const {name , value} = e.target;
        
        this.setState({
            observacionesApertura  : value
        })
    }

    sendAbrirCaja(imprimir){ //SI IMPRIMIR ES 1 IMPRIMIR, SI NO, NO IMPRIMIR
        this.modalAbrirCajaVenta();
        
        cogoToast.loading(
            <div>
                <h4>APERTURANDO CAJA</h4>
            </div>, 
            {
                position: 'top-right'
            }
            
        )
        .then(() => {
            fetch(`/caja/venta/aperturar`,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token'                    : csrf_token,
                        'observacionesApertura'     : this.state.observacionesApertura,    
                        'totalApertura'             : this.state.totalApertura,
                        'totalAperturo'             : this.state.totalAperturo,
                    }),
                    headers: {
                        'Accept'        : 'application/json',
                        'Content-Type'  : 'application/json',
                    }
                }
            )
            .then(response => response.json())
            .then(data => {
                
                if(data['respuesta']){
                    cogoToast.success(
                        <div>
                            <h4>LA APERTURA DE CAJA FUE EXITOSA</h4>
                        </div>, 
                        {
                        position: 'top-right'
                        }
                    );
                    this.setState({
                        // estadoModalAbrirCajaVenta  : !this.state.estadoModalAbrirCajaVenta,
                        totalAperturo              : '',
                        observacionesApertura      : '',
                    });

                    if(imprimir == 1){
                        let url = `http://localhost/api/imprimir/aperturarCaja/`+data['idCaja'];
                        cogoToast.loading(
                            <div>
                                <h4>IMPRIMIENDO APERTURA DE CAJA</h4>
                            </div>, 
                            {
                                position: 'top-right'
                            }
                            
                        )
                        .then(() => {
                            fetch(
                                url
                            )
                            .then(
                                res => res.json()
                            )
                            .then(
                                data => {
                                    if(data['respuesta']){
                                        cogoToast.success(
                                            <div>
                                                <h4>COPIA DE APERTURA DE CAJA FINALIZADA</h4>
                                            </div>, 
                                            {
                                            position: 'top-right'
                                            }
                                        );
                                    }else{
                                        cogoToast.error(
                                            <div>
                                                <h4>NO SE PUDO CONECTAR CON LA IMPRESORA</h4>
                                            </div>, 
                                            {
                                            position: 'top-right'
                                            }
                                        );
                                    }
                                }
                            )
                        });
                    }

                    this.getEstadoCajaVent();

                }else{
                    this.modalAbrirCajaVenta()
                    cogoToast.error(
                        <div>
                            <h4>HUBO UN PROBLEMA AL MOMENTO DE APERTURAR CAJA</h4>
                        </div>, 
                        {
                          position: 'top-right'
                        }
                    );
                }
                
            })
        });
        
        
    }

    modalRegistrarGasto(){
        console.log(this.state.estadoModalRegistrarGasto)
        this.setState({
            estadoModalRegistrarGasto  : !this.state.estadoModalRegistrarGasto,
        })
    }

    modalRegistrarIngreso(){
        this.setState({
            estadoModalRegistrarIngreso  : !this.state.estadoModalRegistrarIngreso,
        })
    }

    getCambioGastoIngreso(e){
        const {name , value} = e.target;
        
        this.setState({
            registrarGastoIngreso  : value
        })
    }

    getCambioMotivoGastoIngreso(e){
        const {name , value} = e.target;
        
        this.setState({
            motivoGastoIngreso  : value
        })
    }



    sendRegistrarGasto(){
        console.log("Datos enviados:");
        console.log(this.state.idCajaVenta);
        console.log(this.state.registrarGastoIngreso);
        console.log(this.state.motivoGastoIngreso);



        fetch(`/gasto/registrar`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token'          : csrf_token,
                    'idCajaVenta'     : this.state.idCajaVenta,    
                    'gasto'           : this.state.registrarGastoIngreso,
                    'motivo'          : this.state.motivoGastoIngreso,
                }),
                headers: {
                    'Accept'        : 'application/json',
                    'Content-Type'  : 'application/json',
                }
            }
        )
        .then(response => response.json())
        .then(data => {
            
            console.log(data)
            if(data['respuesta']){
                this.activarToast('El gasto fue registrado correctamente', this.state.colorToastSuccess);
                this.setState({
                    estadoModalRegistrarGasto  : !this.state.estadoModalRegistrarGasto,
                    registrarGastoIngreso              : '',
                    motivoGastoIngreso                : '',
                });

            }else{
                this.activarToast('Hubo un problema al momento de registrar el gasto', this.state.colorToastDanger)
            }
            
        })
    }

    sendRegistrarIngreso(){
        fetch(`/ingreso/registrar`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token'          : csrf_token,
                    'idCajaVenta'     : this.state.idCajaVenta,    
                    'ingreso'         : this.state.registrarGastoIngreso,
                    'motivo'          : this.state.motivoGastoIngreso,
                }),
                headers: {
                    'Accept'        : 'application/json',
                    'Content-Type'  : 'application/json',
                }
            }
        )
        .then(response => response.json())
        .then(data => {
            
            console.log(data)
            if(data['respuesta']){
                this.activarToast('El ingreso fue registrado correctamente', this.state.colorToastSuccess);
                this.setState({
                    estadoModalRegistrarIngreso  : !this.state.estadoModalRegistrarIngreso,
                    registrarGastoIngreso      : '',
                    motivoGastoIngreso         : '',
                });

            }else{
                this.activarToast('Hubo un problema al momento de registrar el ingreso', this.state.colorToastDanger)
            }
            
        })
    }

    activarToast(message, color) {
        this.setState({
            mensajeToast : message,
            colorToast   : color,
            estadoToast  : true
        });
    }

    render(){
        return(
            <header className="topbar">
                <nav className="navbar top-navbar navbar-expand-md navbar-dark">
                    <div className="navbar-header">
                        <a className="nav-toggler waves-effect waves-light d-block d-md-none" href="#"><i className="ti-menu ti-close"></i></a>
                        <a className="navbar-brand d-block d-md-none" href="#">
                            <b className="logo-icon">
                                <i className="wi wi-sunset"></i> 
                                <img src="/assetsAdminTemplate/assets/images/logos/logo-icon.png" alt="homepage" className="dark-logo" />
                                <img src="/assetsAdminTemplate/assets/images/logos/logo-light-icon.png" alt="homepage" className="light-logo" />
                            </b>
                            <span className="logo-text">
                                    <img src="/assetsAdminTemplate/assets/images/logos/logo-text.png" alt="homepage" className="dark-logo" />
                                    <img src="/assetsAdminTemplate/assets/images/logos/logo-light-text.png" className="light-logo" alt="homepage" />
                            </span>
                        </a>
                        <div className="d-none d-md-block text-center">
                            <a className="sidebartoggler waves-effect waves-light d-flex align-items-center side-start" href="#" data-sidebartype="mini-sidebar">
                                <i className="mdi mdi-menu"></i>
                                <span className="navigation-text ml-3"> Navegador</span>
                            </a>
                        </div>
                        <a className="topbartoggler d-block d-md-none waves-effect waves-light" href="#" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i className="ti-more"></i></a>
                    </div>
                    <div className="navbar-collapse collapse" id="navbarSupportedContent">
                        <ul className="navbar-nav float-left mr-auto">
                            <li className="nav-item">
                                <a className="nav-link navbar-brand d-none d-md-block" href="#">
                                    <b className="logo-icon">
                                        <i className="wi wi-sunset"></i>
                                        <img src="/assetsAdminTemplate/assets/images/logos/logo-icon.png" alt="homepage" className="dark-logo" />
                                        <img src="/assetsAdminTemplate/assets/images/logos/logo-light-icon.png" alt="homepage" className="light-logo" />
                                    </b>
                                    <span className="logo-text">
                                            <img src="/assetsAdminTemplate/assets/images/logos/logo-text.png" alt="homepage" className="dark-logo" />
                                            <img src="/assetsAdminTemplate/assets/images/logos/logo-light-text.png" className="light-logo" alt="homepage" />
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <ul className="navbar-nav float-right">
                            <li className="nav-item ">
                                <div className="nav-link dropdown-toggle waves-effect waves-dark" aria-expanded="false">
                                    <button 
                                        type="button" 
                                        className="btn waves-effect waves-light btn-rounded btn-warning"
                                        onClick={
                                            this.modalRegistrarIngreso
                                        }
                                        >
                                            Registrar Ingreso
                                    </button>
                                </div>
                            </li>
                            <li className="nav-item ">
                                <div className="nav-link dropdown-toggle waves-effect waves-dark" aria-expanded="false">
                                    <button 
                                        type="button" 
                                        className="btn waves-effect waves-light btn-rounded btn-warning"
                                        onClick={
                                            this.modalRegistrarGasto
                                        }
                                        >
                                            Registrar Gasto
                                    </button>
                                </div>
                            </li>
                            <li className="nav-item dropdown mega-dropdown">
                                <div className="nav-link dropdown-toggle waves-effect waves-dark" aria-expanded="false">
                                    {
                                        this.state.estadoCajaVenta == true
                                        ?<button 
                                            type="button" 
                                            id="btn_abrirCerrarCaja"
                                            className="btn waves-effect waves-light btn-rounded btn-primary"
                                            onClick={this.abrirCerrarCajaVenta}
                                            >
                                                {this.state.txt_btn_abriCerrarCaja}
                                        </button>
                                        :<button>asd</button>
                                    }
                                    
                                </div>
                            </li>
                            <li className="nav-item dropdown">
                                <a className="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="/assetsAdminTemplate/assets/images/users/1.jpg" alt="user" className="rounded-circle" width="31"/>
                                    <span className="ml-2 user-text font-medium">Endulzate</span><span className="fas fa-angle-down ml-2 user-text"></span></a>

                                <div className="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                    <div className="d-flex no-block align-items-center p-3 mb-2 border-bottom">
                                        <div className=""><img src="/assetsAdminTemplate/assets/images/users/1.jpg" alt="user" className="rounded" width="80" /></div>
                                        <div className="ml-2">
                                            <h4 className="mb-0">Endulzate</h4>
                                            <a href="#" className="btn btn-sm btn-danger text-white mt-2 btn-rounded">Ver perfil</a>
                                        </div>
                                    </div>
                                    <a className="dropdown-item" href="#"><i className="ti-user mr-1 ml-1"></i> Mi perfil</a>
                                    <a className="dropdown-item" href="#"><i className="fa fa-power-off mr-1 ml-1"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

            {/* MODAL DE ABRIR CAJA */}
            <Modal
                size="lg"
                show={this.state.estadoModalAbrirCajaVenta}
                onHide={() => this.modalAbrirCajaVenta()}
                aria-labelledby="contained-modal-title-vcenter"
                centered
                >
                <Modal.Header closeButton>
                    <Modal.Title id="example-custom-modal-styling-title">
                        {this.state.txt_btn_abriCerrarCaja}
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className="card card-default">
                        <div className="modal-body">
                            <div className="card-body">
                                <div className="form-group">
                                    <div className="row">
                                        <div className="col-6">
                                            <label>SUCURSAL</label>
                                            <h4 className="card-title">{this.state.sucursalNombre} </h4>
                                        </div>
                                        <div className="col-6">
                                            <label>USUARIO CONECTADO</label>
                                            <h4 className="card-title"> {this.state.usuarioNombre} </h4>
                                        </div>
                                    </div>
                                </div>
                                <div className="form-group" >
                                    <div className="row">
                                        <div className="col-12">
                                            <label>DINERO EN LA CAJA</label>
                                            <h4 className="card-title">{this.state.totalApertura } </h4>
                                        </div>
                                    </div>
                                </div>
                                <div className="form-group" >
                                    <div className="row"> 
                                        <div className="col-12">
                                            <label>Dinero para abrir caja</label>
                                            <input 
                                                type        = "text" 
                                                className   = "form-control" 
                                                onChange    = {this.getCambioTotalAperturo}
                                                autoFocus
                                                value       = {this.state.totalAperturo} 
                                            />
                                        </div>
                                    </div>
                                </div>
                                
                                <div className="form-group">
                                    <div className="row">
                                        <div className="col-12">
                                            <label>Comentario (opcional)</label>
                                            <textarea 
                                                className   = "form-control" 
                                                rows        = "3" 
                                                placeholder = "Comentario"
                                                onChange    = {this.getCambioObservacion}
                                                value       = {this.state.observacionesApertura}
                                            >

                                            </textarea>
                                        </div>                                        
                                    </div>
                                </div>
                                <div className="form-group">
                                    <div className="row">
                                        <div className="col-12">
                                            <h4 className="card-title">Si el dinero en la caja no cuadra con el del sistema, ingresar sin preocupaciones, dejando un comentario del asunto o acción realiza</h4>
                                        </div>
                                    </div>
                                </div>
                                <div className="form-group boton">
                                    <div className="row">
                                        <div className="col-6">
                                            <button 
                                                type="button" 
                                                className="addexis form-control btn btn-block btn-success btn-lg" 
                                                id="crearProducto" 
                                                onClick={()=>this.sendAbrirCaja(1)}>
                                                APERTURAR E IMPRIMIR CAJA</button>        
                                        </div>
                                        <div className="col-6">
                                            <button 
                                                type="button" 
                                                className="addexis form-control btn btn-block btn-success btn-lg" 
                                                id="crearProducto" 
                                                onClick={()=>this.sendAbrirCaja(0)}>
                                                APERTURAR CAJA</button>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </Modal.Body>
                </Modal>
                


                {/* MODAL DE CERRAR CAJA */}
                <Modal
                    size="lg"
                    show={this.state.estadoModalCerrarCajaVenta}
                    onHide={() => this.modalCerrarCajaVenta()}
                    aria-labelledby="contained-modal-title-vcenter"
                    centered
                    >
                    <Modal.Header closeButton>
                        <Modal.Title id="example-custom-modal-styling-title">
                            {this.state.txt_btn_abriCerrarCaja}
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {
                            
                            <CerrarCajaVentaComponente 
                                idCajaVenta     = {this.state.idCajaVenta}
                                sucursalNombre  = {this.state.sucursalNombre}
                                usuarioNombre   = {this.state.usuarioNombre}
                                totalApertura   = {this.state.totalApertura}
                                totalAperturo   = {this.state.totalAperturo}
                                totalCierre     = {this.state.totalCierre}

                                mensajeToast    = {this.state.mensajeToast}
                                colorToast      = {this.state.colorToast}
                                estadoToast     = {this.state.estadoToast}

                                modalCerrarCajaVenta     = {this.modalCerrarCajaVenta}
                                activarToast             = {this.activarToast}
                                getEstadoCajaVent        = {this.getEstadoCajaVent}

                                loadModalCierreCajaVenta =   {this.state.loadModalCierreCajaVenta}
                            />
                            
                        }
                        
                        
                    </Modal.Body>
                </Modal>
                
                {/* MODAL DE REGISTRAR GASTO O INGRESO*/}
                <Modal
                    size="lg"
                    show={this.state.estadoModalRegistrarGasto}
                    onHide={() => this.modalRegistrarGasto()}
                    aria-labelledby="contained-modal-title-vcenter"
                    centered
                    >
                    <Modal.Header closeButton>
                        <Modal.Title id="example-custom-modal-styling-title">
                            Registrar Gasto
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="card card-default">
                            <div className="modal-body">
                                <div className="card-body">
                                    <div className="form-group" >
                                        <div className="row"> 
                                            <div className="col-12">
                                                <label>Gasto en soles </label>
                                                <input 
                                                    type        = "text" 
                                                    className   = "form-control" 
                                                    onChange    = {this.getCambioGastoIngreso}
                                                    autoFocus
                                                    value       = {this.state.registrarGastoIngreso} 
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="form-group">
                                        <div className="row">
                                            <div className="col-12">
                                                <label>Motivo del gasto</label>
                                                <textarea 
                                                    className   = "form-control" 
                                                    rows        = "3" 
                                                    placeholder = "Motivo del gasto"
                                                    onChange    = {this.getCambioMotivoGastoIngreso}
                                                    value       = {this.state.motivoGastoIngreso}
                                                >

                                                </textarea>
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div className="form-group boton">
                                        <button 
                                            type="button" 
                                            className="addexis form-control btn btn-block btn-success btn-lg" 
                                            onClick={()=>this.sendRegistrarGasto()}>
                                            Registrar Gasto</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Modal.Body>
                    </Modal>
                    {/* MODAL DE REGISTRAR INGRESO*/}
                    <Modal
                        size="lg"
                        show={this.state.estadoModalRegistrarIngreso}
                        onHide={() => this.modalRegistrarIngreso()}
                        aria-labelledby="contained-modal-title-vcenter"
                        centered
                        >
                        <Modal.Header closeButton>
                            <Modal.Title id="example-custom-modal-styling-title">
                                Registrar Ingreso
                            </Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                            <div className="card card-default">
                                <div className="modal-body">
                                    <div className="card-body">
                                        <div className="form-group" >
                                            <div className="row"> 
                                                <div className="col-12">
                                                    <label>Ingreso en soles </label>
                                                    <input 
                                                        type        = "text" 
                                                        className   = "form-control" 
                                                        onChange    = {this.getCambioGastoIngreso}
                                                        autoFocus
                                                        value       = {this.state.registrarGastoIngreso} 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="form-group">
                                            <div className="row">
                                                <div className="col-12">
                                                    <label>Motivo del ingreso de dinero</label>
                                                    <textarea 
                                                        className   = "form-control" 
                                                        rows        = "3" 
                                                        placeholder = "Motivo del gasto"
                                                        onChange    = {this.getCambioMotivoGastoIngreso}
                                                        value       = {this.state.motivoGastoIngreso}
                                                    >

                                                    </textarea>
                                                </div>                                        
                                            </div>
                                        </div>
                                        <div className="form-group boton">
                                            <button 
                                                type="button" 
                                                className="addexis form-control btn btn-block btn-success btn-lg" 
                                                onClick={()=>this.sendRegistrarIngreso()}>
                                                Registrar Ingreso</button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Modal.Body>
                        </Modal>

                <Toast
                    style={{
                        position        : 'fixed',
                        top             : 20,
                        right           : 20,
                        zIndex          :1060,
                        backgroundColor : this.state.colorToast,
                        color           :'white'
                    }}
                    onClose={() => this.setState({
                        estadoToast: false
                    })}
                    show    ={this.state.estadoToast}
                    delay   ={2500}
                    autohide
                    >
                    <Toast.Body>
                    <h3>{this.state.mensajeToast}</h3>
                    </Toast.Body>
                </Toast>
            </header>
            
        )
    }  
}

export default Top
