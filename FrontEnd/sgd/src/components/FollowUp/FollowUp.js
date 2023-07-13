import React, { useState, useEffect, useContext } from 'react';
import { fetchJson } from '../../hooks/useFetchJson';
import { UpdateFollowUp } from './UpdateFollowUp';
import { AssignProcess } from './AssignProcess/AssignProcess';
import { AssignAction } from './AssignAcction/AssignAction';
import { DocArticle } from './DocArticulo/DocArticle';
import { AuthContext } from '../Context/AuthContext';



export const FollowUp = () => {
    const { loginData} = useContext(AuthContext);
    //Varibles de dataes y tabla
    const [dataTable, setDataTable] = useState([]);
    const [dataUrl, setDataUrl] = useState([]);
    const [filteredDataTable, setFilteredDataTable] = useState([]);

    const [currentPage, setCurrentPage] = useState(1);
    const recordsPerPage = 10;
    //variable de Modales
    const [showModalUpdate, setshowModalUpdate] = useState(false);

    const [showTabsAssignProcess, setshowTabsAssignProcess] = useState(false);
    const [showTabsAssignAction, setshowTabsAssignAction] = useState(false);
    const [showTabsDocArticle, setshowTabsDocArticle] = useState(false);
    //Varible datae selecionado
    const [selectedDataRow, setSelectedDataRow] = useState(null);

    const fetchDataList = async () => {
        try {
            const requestData = {
                idClient: loginData.clientId,
                idArticle: "",
                idProcess: ""
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiArticle/apiArticle.php/listArticle',
                requestData
            );

            if (Array.isArray(responseData.data) && responseData.data.length > 0) {
                setDataTable(responseData.data);
                setFilteredDataTable(responseData.data);
            } else {
                setDataTable([]);
                setFilteredDataTable([]);
            }
        } catch (error) {
            console.error(error);
        }
    };

    const fetchDataDownload = async (idFile, nameFile) => {
        try {
            const requestData = {
                idFile: idFile
            };

            if (requestData.idFile == 'NULL') {
                return;
            }

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiFile/apiFile.php/downloadFile',
                requestData
            );

            if (responseData.desError === 'Consulta exitosa') {
                const fileUrl = responseData.data;
                const fileName = nameFile;
                initiateDownload(fileUrl, fileName);
            } else {
                setDataUrl([]);
            }
        } catch (error) {
            console.error(error);
        }
    };

    const initiateDownload = (fileUrl, fileName) => {
        fetch(fileUrl)
            .then(response => response.blob())
            .then(blob => {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', fileName);
                link.click();
            })
            .catch(error => {
                console.error(error);
            });
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    const indexOfLastRecord = currentPage * recordsPerPage;
    const indexOfFirstRecord = indexOfLastRecord - recordsPerPage;
    const currentRecords = filteredDataTable.slice(indexOfFirstRecord, indexOfLastRecord);

    const handleReload = (data) => {
        fetchDataList();
    };

    const handleEditButtonClick = (data) => {
        setSelectedDataRow(data);
        setshowModalUpdate(true);
    };

    const handleAssignButtonClick = (data) => {
        setshowTabsAssignProcess(false);
        setshowTabsAssignAction(false);
        setshowTabsDocArticle(false);
        setTimeout(() => {
            setSelectedDataRow(data);
            setshowTabsAssignProcess(true);
            setshowTabsAssignAction(true);
            setshowTabsDocArticle(true);
        }, 100);
    };

    const closeModalEdit = () => {
        setshowModalUpdate(false);
        fetchDataList();
    };

    const closeTabsAssignProcess = () => {
        setshowTabsAssignProcess(false);
    };

    const closeTabsAssignAction = () => {
        setshowTabsAssignAction(false);
    };

    const closeTabsDocArticle = () => {
        setshowTabsDocArticle(false);
    };

    useEffect(() => {
        fetchDataList();
    }, []);

    const onChangeFilter = (selectedOption) => {

        if (selectedOption !== '') {

            const filteredDataTable = dataTable.filter(
                (data) =>
                    data.client_id === selectedOption
            );
            setFilteredDataTable(filteredDataTable);

        } else {
            setFilteredDataTable(dataTable);
        }
        setCurrentPage(1);
    };

    return (

        <div>
            <section className="content-header" >
                <div className="container-fluid" >
                    <div className="row mb-2">
                        <div className="col-sm-6">
                            <h1>Seguimiento</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="card card-solid">
                    <div className="card card-info">
                        <div className="card-header">
                            <h3 className="card-title">Lista de seguimiento de artículos</h3>
                        </div>
                        <div className="card-body">
                            <div
                                id="example1_wrapper"
                                className="dataTables_wrapper dt-bootstrap4"
                            >
                                <div className="row">
                                    <div className="col-sm-12 col-md-6 d-flex justify-content-end">
                                        <div
                                            id="example1_filter"
                                            className="dataTables_filter"
                                        >
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-12 table-responsive">
                                        <table
                                            id="example1"
                                            className="table table-bordered table-striped dataTable dtr-inline"
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
                                                        ID DE ARÍCULO
                                                    </th>
                                                    <th
                                                        className="sorting_asc"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        NOMBRE DE ARTÍCULO
                                                    </th>
                                                    <th
                                                        className="sorting_asc"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        DETALLE DE ARTÍCULO
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"

                                                    >
                                                        APLICA
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        JUSTIFICACIÓN
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        DOCUMENTO DE ARTÍCULO
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        PROCESOS
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        DOCUMENTO DE PROCESO
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '100px' }}
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
                                                        style={{ minWidth: '100px' }}
                                                    >
                                                        NIVEL DE CUMPLIMIENTO
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        ACCIONES
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        DOCUMENTO DE ACCIONES
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                        style={{ minWidth: '200px' }}
                                                    >
                                                        OBSERVACIONES
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        FECHA DE MODIFICACIÓN
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
                                                        STATUS APPLY
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
                                                        STATUS ID
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
                                                        COMPLIANCE ID
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {currentRecords.map((data) => (
                                                    <tr key={data.article_id}>
                                                        <td>{data.article_id}</td>
                                                        <td>{data.article_name}</td>
                                                        <td>{data.article_description}</td>
                                                        <td>{data.article_apply_name}</td>
                                                        <td>{data.article_justification}</td>
                                                        <td>
                                                            <ul>
                                                                {data.documentoarticulo &&
                                                                    data.documentoarticulo
                                                                        .slice(1, -1)
                                                                        .split(",")
                                                                        .map((docArticle, index) => {
                                                                            const fileNamed = docArticle.replace(/"/g, "");
                                                                            const fileId = data.documentoarticuloids.slice(1, -1).split(",")[index].replace(/"/g, "");

                                                                            return (
                                                                                <div key={index}>
                                                                                    <li>
                                                                                        <a
                                                                                            className='nav-link '
                                                                                            href='#'
                                                                                            onClick={() => fetchDataDownload(fileId, fileNamed)}>{fileNamed}
                                                                                        </a>
                                                                                    </li>

                                                                                </div>
                                                                            );
                                                                        })}
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <ul>
                                                                {data.process_names &&
                                                                    data.process_names
                                                                        .slice(1, -1)
                                                                        .split(",")
                                                                        .map((process, index) => (
                                                                            <li key={index}>{process.replace(/"/g, "")}</li>
                                                                        ))}
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <ul>
                                                                {data.documentoprocesos &&
                                                                    data.documentoprocesos
                                                                        .slice(1, -1)
                                                                        .split(",")
                                                                        .map((docProcess, index) => {
                                                                            const fileNamed = docProcess.replace(/"/g, "");
                                                                            const fileId = data.documentoprocesosids.slice(1, -1).split(",")[index].replace(/"/g, "");

                                                                            return (
                                                                                <div key={index}>
                                                                                    <li>
                                                                                        <a
                                                                                            className='nav-link '
                                                                                            href='#'
                                                                                            onClick={() => fetchDataDownload(fileId, fileNamed)}>{fileNamed}
                                                                                        </a>
                                                                                    </li>

                                                                                </div>
                                                                            );
                                                                        })}
                                                            </ul>
                                                        </td>
                                                        <td>{data.article_status_name}</td>
                                                        <td>{data.article_compliance_name}</td>
                                                        <td>
                                                            <ul>
                                                                {data.actions &&
                                                                    data.actions
                                                                        .slice(1, -1)
                                                                        .split(",")
                                                                        .map((actions, index) => (
                                                                            <li key={index}>{actions.replace(/"/g, "")}</li>
                                                                        ))}
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <ul>

                                                                {data.documentoacciones &&
                                                                    data.documentoacciones
                                                                        .slice(1, -1)
                                                                        .split(",")
                                                                        .map((docActions, index) => {
                                                                            const fileNamed = docActions.replace(/"/g, "");
                                                                            const fileId = data.documentoaccionesids.slice(1, -1).split(",")[index].replace(/"/g, "");

                                                                            return (
                                                                                <div key={index}>
                                                                                    <li>
                                                                                        <a
                                                                                            className='nav-link '
                                                                                            href='#'
                                                                                            onClick={() => fetchDataDownload(fileId, fileNamed)}>{fileNamed}
                                                                                        </a>
                                                                                    </li>

                                                                                </div>
                                                                            );
                                                                        })}
                                                            </ul>
                                                        </td>
                                                        <td>{data.article_observation}</td>
                                                        <td>{data.article_update_at.slice(0, 19)}</td>
                                                        <td className="text-center">
                                                            <button
                                                                type="button"
                                                                className="btnEdit btn btn-sm btn-outline-primary mr-1"
                                                                title="Editar"
                                                                onClick={() => handleEditButtonClick(data)}
                                                            >
                                                                <i
                                                                    className="nav-icon far fa-edit"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                className="btnAssign btn btn-sm btn-outline-primary mr-1"
                                                                title="Asignar"
                                                                onClick={() => handleAssignButtonClick(data)}
                                                            >
                                                                <i
                                                                    className="nav-icon fas fa-list-ul"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.article_apply}
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.status_id}
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.article_compliance}
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
                                            {Math.min(indexOfLastRecord, filteredDataTable.length)} de{' '}
                                            {filteredDataTable.length} registros
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
                                                    { length: Math.ceil(filteredDataTable.length / recordsPerPage) },
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
                                                        Math.ceil(filteredDataTable.length / recordsPerPage)
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
            {showModalUpdate && (
                <UpdateFollowUp selectedDataRow={selectedDataRow} closeModalEdit={closeModalEdit} />
            )}
            {showTabsAssignProcess && (
                <AssignProcess selectedDataRow={selectedDataRow} closeTabsAssignProcess={closeTabsAssignProcess} handleReload={handleReload} />
            )}
            {showTabsAssignAction && (
                <AssignAction selectedDataRow={selectedDataRow} closeTabsAssignAction={closeTabsAssignAction} handleReload={handleReload} />
            )}
            {showTabsDocArticle && (
                <DocArticle selectedDataRow={selectedDataRow} closeTabsAssignAction={closeTabsDocArticle} handleReload={handleReload} />
            )}
        </div >
    );
};
