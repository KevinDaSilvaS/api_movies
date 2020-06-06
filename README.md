# api_movies
Api rest entirely made in php to manage movies,actors and directors.Test made for the php developer role at magic web design

<h3>Iniciando o projeto:</h3>
<p>Basta apenas fazer o download do repositório e rodar o aqrquivo .sql dentro do Mysql 
com os dados do arquivo config.php na pasta do IvoryV1 e o projeto ja está funcionando</p>

<h3>Processo de Desenvolvimento:</h3>
<ul>
  <li>
    <p>Devido ao meu pouco contato com o framework Laravel e devido ao tempo (onde só pude reservar o sabado para o desenvolvimento da    solução) infelizmente não foi possivel uma pesquisa mais aprofundada sobre o processo de criação de APIS com o Laravel e a fiz utilizando a linguagem em sua forma mais purista com o auxilio de OOP,PDO e um mini ORM que estou desenvolvendo no meu tempo livre.</p>
   </li>
  <li>
    <p>Acredito que durante o processo realmente pequei em manter algumas partes do meu código DRY(dont repeat yourself e acabei repetindo códigos de validação em algumas requests em uma situação com mais tempo teria desaclopado em um arquivo só para validações).</p>
    </li>
    <li>
     <p>No desenvolvimento da api de filmes notei a necessidade de criar classes auxiliares porque meu código estava ficando gigantesco e ilegivél o que facilitou algumas validações e operações.Queria ter obtido tempo de aplicar algum pattern organizacional melhor como um strategy ou algo do genero.</p>
    </li>
  <li>
    <p>Acredito que numa situação ideal deveria ter criado uma classe com um crud para cada tabela para facilitar o processo das consultas e não precisar faze-las no momento em que se recebe a request</p>
    </li>
  
</ul>
