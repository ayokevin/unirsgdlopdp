import React, { useState, useEffect } from 'react';
import { fetchJson } from '../../hooks/useFetchJson';
import { UpdateClient } from './UpdateClient';
import { InsertClient } from './InsertClient';
import SearchClient from './SearchClient';

export const Client = () => {
    //Varibles de clientes y tabla
    const [clients, setClients] = useState([]);
    const [filteredClients, setFilteredClients] = useState([]);

    const [currentPage, setCurrentPage] = useState(1);
    const recordsPerPage = 20;
    //variable de Modales
    const [showModalUpdateClient, setshowModalUpdateClient] = useState(false);
    const [showModalInsertClient, setshowModalInsertClient] = useState(false);
    //Varible Cliente selecionado
    const [selectedClient, setSelectedClient] = useState(null);

    useEffect(() => {
        fetchDataListClient();
    }, []);

    const fetchDataListClient = async () => {
        try {
            const requestData = {};

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiClient/apiClient.php/listClient',
                requestData
            );
            if (Array.isArray(responseData.data) && responseData.data.length > 0) {
                setClients(responseData.data);
                setFilteredClients(responseData.data);
            } else {
                setClients([]);
                setFilteredClients([]);
            }
        } catch (error) {
            console.error(error);
        }
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    const indexOfLastRecord = currentPage * recordsPerPage;
    const indexOfFirstRecord = indexOfLastRecord - recordsPerPage;
    const currentRecords = filteredClients.slice(indexOfFirstRecord, indexOfLastRecord);

    const handleUpdateClientButtonClick = (client) => {
        setSelectedClient(client);
        setshowModalUpdateClient(true);
    };

    const closeModalUpdateClient = () => {
        setshowModalUpdateClient(false);
        fetchDataListClient();
    };

    const handleInsertClientButtonClick = (client) => {
        setshowModalInsertClient(true);
    };

    const closeModalInsertClient = () => {
        setshowModalInsertClient(false);
        fetchDataListClient();
    };

    useEffect(() => {
        setshowModalUpdateClient(!!selectedClient);
    }, [selectedClient]);

    const onChangeFilter = (selectedOption) => {
        if (selectedOption !== '') {
            const filteredClients = clients.filter(
                (client) =>
                    client.client_id === selectedOption
            );
            setFilteredClients(filteredClients);
        } else {
            setFilteredClients(clients);
        }
        setCurrentPage(1);
    };

    return (
        <div>
            <section className="content-header ">
                <div className="container-fluid">
                    <div className="row mb-2">
                        <div className="col-sm-6">
                            <h1>Administración del cliente</h1>
                        </div>
                    </div>
                </div>
            </section>
            <SearchClient clients={clients} onChangeFilter={onChangeFilter} />
            <section className="content ">
                <div className="card card-solid">
                    <div className="card card-success">
                        <div className="card-header">
                            <h3 className="card-title">Lista del cliente</h3>
                        </div>
                        <div className="card-body">
                            <div
                                id="example1_wrapper"
                                className="dataTables_wrapper dt-bootstrap4 "
                            >
                                <div className="row">
                                    <div className="col-sm-12 col-md-6 ">
                                        <div className="dt-buttons btn-group flex-wrap">
                                            <button className="btn btn-secondary"
                                                tabIndex={0}
                                                aria-controls="tbActiveClients"
                                                type="button"
                                                onClick={handleInsertClientButtonClick}>
                                                <span><i className="nav-icon fas fa-user-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div className="col-sm-12 col-md-6 d-flex justify-content-end">
                                        <div
                                            id="example1_filter"
                                            className="dataTables_filter"
                                        >
                                        </div>
                                    </div>
                                </div>
                                <div className="row ">
                                    <div className="col-sm-12">
                                        <table
                                            id="example1"
                                            className="table table-bordered table-striped dataTable dtr-inline "
                                            role="grid"
                                            aria-describedby="example1_info"
                                        >
                                            <thead>
                                                <tr role="row">
                                                    <th
                                                        className="sorting_asc"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending"
                                                    >
                                                        ID CLIENTE
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        NOMBRE
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        RUC
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        ESTADO
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        ACCIONES
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Platform(s): activate to sort column ascending"
                                                        style={{ display: 'none' }}
                                                    >
                                                        STATUS_ID
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {currentRecords.map((client) => (
                                                    <tr key={client.client_id}>
                                                        <td>{client.client_id}</td>
                                                        <td>{client.client_name}</td>
                                                        <td>{client.client_ruc}</td>
                                                        <td>{client.reference_name}</td>
                                                        <td className="text-center">
                                                            <button
                                                                type="button"
                                                                className="btnEdit btn btn-sm btn-outline-primary mr-1"
                                                                title="Editar"
                                                                onClick={() => handleUpdateClientButtonClick(client)}
                                                            >
                                                                <i
                                                                    className="nav-icon far fa-edit"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {client.status_id}
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-12 col-md-5">
                                        <div
                                            className="dataTables_info"
                                            id="example1_info"
                                            role="status"
                                            aria-live="polite"
                                        >
                                            Mostrando {indexOfFirstRecord + 1} a{' '}
                                            {Math.min(indexOfLastRecord, filteredClients.length)} de{' '}
                                            {filteredClients.length} registros
                                        </div>
                                    </div>
                                    <div className="col-sm-12 col-md-7">
                                        <div
                                            className="dataTables_paginate paging_simple_numbers"
                                            id="example1_paginate"
                                        >
                                            <ul className="pagination">
                                                <li
                                                    className={`paginate_button page-item ${currentPage === 1 ? 'disabled' : ''
                                                        }`}
                                                >
                                                    <button
                                                        className="page-link"
                                                        onClick={() => handlePageChange(currentPage - 1)}
                                                    >
                                                        Anterior
                                                    </button>
                                                </li>
                                                {Array.from(
                                                    { length: Math.ceil(filteredClients.length / recordsPerPage) },
                                                    (_, i) => (
                                                        <li
                                                            className={`paginate_button page-item ${currentPage === i + 1 ? 'active' : ''
                                                                }`}
                                                            key={i}
                                                        >
                                                            <button
                                                                className="page-link"
                                                                onClick={() => handlePageChange(i + 1)}
                                                            >
                                                                {i + 1}
                                                            </button>
                                                        </li>
                                                    )
                                                )}
                                                <li
                                                    className={`paginate_button page-item ${currentPage ===
                                                        Math.ceil(filteredClients.length / recordsPerPage)
                                                        ? 'disabled'
                                                        : ''
                                                        }`}
                                                >
                                                    <button
                                                        className="page-link"
                                                        onClick={() => handlePageChange(currentPage + 1)}
                                                    >
                                                        Siguiente
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {showModalUpdateClient && (
                <UpdateClient selectedClient={selectedClient} closeModalUpdateClient={closeModalUpdateClient} />
            )}
            {showModalInsertClient && (
                <InsertClient closeModalInsertClient={closeModalInsertClient} />
            )}
        </div >
    );
};
