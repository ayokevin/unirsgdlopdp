import React from 'react'

export const Contact = () => {
    return (
        <div>
            <section className="content-header">
                <div className="container-fluid">
                    <div className="row mb-2">
                        <div className="col-sm-6">
                            <h1>Contactos</h1>
                        </div>
                        <div className="col-sm-6">
                            <ol className="breadcrumb float-sm-right">
                                <li className="breadcrumb-item"><a href="/main">Inico</a></li>
                                <li className="breadcrumb-item active">Contactos</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section className="content">
                <div className="card card-solid">
                    <div className="card-body pb-0">
                        <div className="row">
                            <div className="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div className="card bg-light d-flex flex-fill">
                                    <div className="card-header text-muted border-bottom-0">
                                        Desarollador
                                    </div>
                                    <div className="card-body pt-0">
                                        <div className="row">
                                            <div className="col-7">
                                                <h2 className="lead"><b>Kevin Santillan</b></h2>
                                                <p className="text-muted text-sm"><b>Descripción: </b> Desarollador Web / UX / Estudiante de Unir</p>
                                                <ul className="ml-4 mb-0 fa-ul text-muted">
                                                    <li className="small"><span className="fa-li"><i className="fas fa-lg fa-building"></i></span> Direccion: Ecuador-Quito</li>
                                                    <li className="small"><span className="fa-li"><i className="fas fa-lg fa-phone"></i></span> Celular:+593960543743</li>
                                                </ul>
                                            </div>
                                            <div className="col-5 text-center">
                                                <img src="dist\img\SGD\userKevin.jpg" alt="user-avatar" className="img-circle img-fluid"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="card-footer">
                                        <div className="text-right">
                                            <a href="https://wa.me/593960543743" rel="noreferrer" target="_blank" className="btn btn-sm bg-teal">
                                                <i className="fas fa-comments"></i>
                                            </a>
                                            <a href="https://www.linkedin.com/in/kevin-paul-santillan-delgado-b577a8167/" className="btn btn-sm btn-primary" rel="noreferrer" target="_blank">
                                                <i className="fas fa-user"></i> Ver perfil
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    )
}
