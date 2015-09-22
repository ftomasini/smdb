#
 
Principal() 
{
  echo "Bem vindo"
  echo "------------------------------------------"
  echo "Opções:"
  echo
  echo "1. Instalar aplicação"
  echo "2. Atualizar"
  echo "3. Deletar"
  echo "4. Sair"
  echo
  echo -n "Qual a opção desejada? "
  read opcao
  case $opcao in
    1) Instalar ;;
    2) Atualizar ;;
    3) Deletar ;;
    4) exit ;;
    *) "Opção desconhecida." ; echo ; Principal ;;
  esac
}
 
Instalar() 
{  
    echo 'Instalando pacotes';
    apt-get install git;
    apt-get install postgresql;
    apt-get install apache2;
    apt-get install php5;
    apt-get install php5-pgsql;
    echo 'Clonando projeto do repositório';
    git clone https://ftomasini@bitbucket.org/ftomasini/tcc.git;
    echo 'Criando base de dados'
    createdb -Upostgres smbd
    echo 'Criando tabelas';
    psql -Upostgres -f smdb/data/contacts.sql;
    psql -Upostgres -f smdb/data/usuario.sql;
}

Deletar()
{
    echo 'Not implemented';
}

Atualizar()
{
    echo 'Not implemented';
}


Principal
