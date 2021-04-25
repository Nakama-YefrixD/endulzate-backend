import React, { Component } from 'react'
import { Link } from 'react-router-dom'

class Sidebar extends Component{

    constructor(){
        super();
        this.state ={



        }
        this.fetchLogout               = this.fetchLogout.bind(this);

    }
    fetchLogout(){
        fetch(`/logout`,
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
            window.location.href = window.location.href;

        })
    }

    render(){
        return(
            <aside className="material left-sidebar">
                <div className="scroll-sidebar">
                    <nav className="sidebar-nav">
                        <ul id="sidebarnav">
                            <li className="sidebar-item">
                                <a className="sidebar-link has-arrow waves-effect waves-dark profile-dd" href="javascript:void(0)" aria-expanded="false">
                                    <img src="/assetsAdminTemplate/assets/images/users/1.jpg" className="rounded-circle ml-2" width="30" />
                                    <span className="hide-menu">Endulzate</span>
                                </a>
                                <ul aria-expanded="false" className="collapse  first-level">
                                    <li className="sidebar-item">
                                        <a href="javascript:void(0)" className="sidebar-link">
                                            <i className="ti-user"></i>
                                            <span className="hide-menu"> Mi perfil </span>
                                        </a>
                                    </li>

                                    <li className="sidebar-item">
                                        <a href="javascript:void(0)" className="sidebar-link" onClick={this.fetchLogout}>
                                            <i className="fas fa-power-off"></i>
                                            <span className="hide-menu"> Logout </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li className="sidebar-item">
                                <a className="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i className="mdi mdi-apps"></i>
                                    <span className="hide-menu">ALMACÉN</span>
                                </a>
                                <ul aria-expanded="false" className="collapse first-level">
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/almacen">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                                <span className="hide-menu" >
                                                    Almacén
                                                </span>
                                        </Link>
                                    </li>
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/almacen/transferencias">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                            <span className="hide-menu" >Transferencias</span>
                                        </Link>
                                    </li>
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/almacen/entrada">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                            <span className="hide-menu" >Entradas</span>
                                        </Link>
                                    </li>
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/almacen/proveedor">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                            <span className="hide-menu" >Proveedores</span>
                                        </Link>
                                    </li>
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/almacen/tiposproductos">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                            <span className="hide-menu">Tipos de producto</span>
                                        </Link>
                                    </li>
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/almacen/marcas">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                            <span className="hide-menu" >Marcas</span>
                                        </Link>
                                    </li>
                                </ul>
                            </li>

                            




                            <li className="sidebar-item">
                                <a className="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i className="mdi mdi-shopping"></i>
                                    <span className="hide-menu">VENTAS</span>
                                </a>
                                <ul aria-expanded="false" className="collapse first-level">
                                    <li className="sidebar-item">
                                        <Link className="sidebar-link waves-effect waves-dark sidebar-link" href="#" aria-expanded="false"  to="/ventas">
                                            <i className="mdi mdi-calendar-check"></i>
                                            <span className="hide-menu">Ventas</span>
                                        </Link>
                                    </li>
                                    <li className="sidebar-item">
                                        <Link className="sidebar-link waves-effect waves-dark sidebar-link" href="#" aria-expanded="false"  to="/ventasdetalladas">
                                            <i className="mdi mdi-calendar-check"></i>
                                            <span className="hide-menu">Ventas Detalladas</span>
                                        </Link>
                                    </li>
                                </ul>
                            </li>

                            <li className="sidebar-item">
                                <Link href="app-chats.html" className="sidebar-link" to="/seguimientos">
                                    <i className="mdi mdi-notification-clear-all"></i>
                                    <span className="hide-menu" >SEGUIMIENTO DE PRODUCTO</span>
                                </Link>
                            </li>
                            
                            
                            <li className="sidebar-item">
                                <a className="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i className="mdi mdi-table"></i>
                                    <span className="hide-menu">CAJAS</span>
                                </a>
                                <ul aria-expanded="false" className="collapse first-level">
                                    <li className="sidebar-item">
                                        <Link className="sidebar-link waves-effect waves-dark sidebar-link" href="#" aria-expanded="false"  to="/caja">
                                            <i className="mdi mdi-calendar-check"></i>
                                            <span className="hide-menu">Gestionar Caja</span>
                                        </Link>
                                    </li>

                                    <li className="sidebar-item">
                                        <Link className="sidebar-link waves-effect waves-dark sidebar-link" href="#" aria-expanded="false"  to="/caja/ingresos">
                                            <i className="mdi mdi-calendar-check"></i>
                                            <span className="hide-menu">Gestionar Ingresos</span>
                                        </Link>
                                    </li>

                                    <li className="sidebar-item">
                                        <Link className="sidebar-link waves-effect waves-dark sidebar-link" href="#" aria-expanded="false"  to="/caja/gastos">
                                            <i className="mdi mdi-calendar-check"></i>
                                            <span className="hide-menu">Gestionar Gastos</span>
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                            
                            <li className="sidebar-item">
                                <a className="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i className="mdi mdi-settings"></i>
                                    <span className="hide-menu">CONFIGURACIÓN</span>
                                </a>
                                <ul aria-expanded="false" className="collapse first-level">
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/configuracion/usuarios">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                            <span className="hide-menu" >Usuarios</span>
                                        </Link>
                                    </li>
                                    <li className="sidebar-item">
                                        <Link href="app-chats.html" className="sidebar-link" to="/configuracion/ofertas">
                                            <i className="mdi mdi-comment-processing-outline"></i>
                                            <span className="hide-menu" >Ofertas</span>
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
        )
    }


}

export default Sidebar
