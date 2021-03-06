<?php

class GPTestModel extends GPNode {
  protected static function getDataTypesImpl() {
    return [
      new GPDataType('name', GPDataType::GP_STRING, true),
      new GPDataType('age', GPDataType::GP_INT),
    ];
  }
}

class GPNodeTest extends GPTest {

  public static function setUpBeforeClass() {
    GPDatabase::get()->beginUnguardedWrites();
    GPNodeMap::addToMapForTest(GPTestModel::class);
  }

  public function testCreate() {
    $model = new GPTestModel();
    $this->assertEmpty($model->getID());
    $model->save();
    $this->assertNotEmpty($model->getID());
  }

  public function testLoadByID() {
    $model = new GPTestModel();
    $this->assertEmpty($model->getID());
    $model->save();
    $model::clearCache();
    $this->assertNotEmpty(GPTestModel::getByID($model->getID()));
    // And from cache
    $this->assertNotEmpty(GPTestModel::getByID($model->getID()));
  }

  public function testLoadByName() {
    $name = 'Weirds Name';
    $model = new GPTestModel(['name' => $name]);
    $this->assertEmpty($model->getID());
    $model->save();
    $model::clearCache();
    $this->assertNotEmpty(GPTestModel::getByName($name));
    // From cache
    $this->assertNotEmpty(GPTestModel::getByName($name));
  }

  /**
   * @expectedException GPException
   */
  public function testLoadByAge() {
    $model = new GPTestModel(['name' => 'name', 'age' => 18]);
    $this->assertEmpty($model->getID());
    $model->save();
    GPTestModel::getByAge(18);
  }

  public function testGetData() {
    $model = new GPTestModel(['name' => 'Foo', 'age' => 18]);
    $this->assertEquals($model->getName(), 'Foo');
    $this->assertEquals($model->getAge(), 18);
    $model->save();
    $model::clearCache();
    $loaded_model = GPTestModel::getByID($model->getID());
    $this->assertEquals(
      $model->getDataArray(),
      ['name' => 'Foo', 'age' => 18]
    );
  }

  public function testSetData() {
    $model = new GPTestModel();
    $model->setName('Bar');
    $model->setAge(25);
    $this->assertEquals(
      $model->getDataArray(),
      ['name' => 'Bar', 'age' => 25]
    );
  }

  public static function tearDownAfterClass() {
    GPNode::simpleBatchDelete(GPTestModel::getAll());
    GPDatabase::get()->endUnguardedWrites();
  }

}
